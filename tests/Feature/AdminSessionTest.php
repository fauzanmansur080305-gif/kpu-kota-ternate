<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_allows_one_session_and_blocks_another_active_device(): void
    {
        $administrator = User::factory()->create([
            'nip' => '1234567890',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'nip' => '1234567890',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('admin_login_token');

        $this->assertNotEmpty($administrator->fresh()->active_login_token);

        $activeLoginToken = $administrator->fresh()->active_login_token;
        $this->assertNotEmpty($activeLoginToken);

        $this->post(route('login.store'), [
            'nip' => '1234567890',
            'password' => 'password',
        ])->assertSessionHasErrors('nip');

        $this->assertSame($activeLoginToken, $administrator->fresh()->active_login_token);
    }

    public function test_admin_logout_starts_a_one_minute_login_cooldown_and_uncached_pages_redirect(): void
    {
        $administrator = User::factory()->create([
            'nip' => '1234567890',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'nip' => '1234567890',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $dashboard = $this->get(route('admin.dashboard'));
        $dashboard->assertOk();
        $this->assertStringContainsString('no-store', $dashboard->headers->get('Cache-Control'));
        $dashboard->assertSee("window.history.pushState({ adminDashboard: true }, '', window.location.href);", false)
            ->assertSee("window.addEventListener('popstate'", false);

        $this->post(route('logout'))->assertRedirect(route('home'));

        $this->assertNull($administrator->fresh()->active_login_token);
        $this->assertNotNull($administrator->fresh()->admin_login_blocked_until);

        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->post(route('login.store'), [
            'nip' => '1234567890',
            'password' => 'password',
        ])->assertSessionHasErrors('nip');

        $this->travel(59)->seconds();

        $this->post(route('login.store'), [
            'nip' => '1234567890',
            'password' => 'password',
        ])->assertSessionHasErrors('nip');

        $this->travel(1)->seconds();

        $this->post(route('login.store'), [
            'nip' => '1234567890',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_member_login_and_dashboard_flow_remain_unchanged(): void
    {
        $member = User::factory()->create([
            'nip' => '9876543210',
            'role' => 'anggota',
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'nip' => $member->nip,
            'password' => 'password',
        ])->assertRedirect(route('anggota.dashboard'));

        $this->assertNull($member->fresh()->active_login_token);
    }

    public function test_admin_can_login_when_database_contains_an_orphaned_login_token(): void
    {
        config(['session.driver' => 'database']);

        $administrator = User::factory()->create([
            'nip' => '1234567890',
            'role' => 'admin',
            'password' => 'password',
            'active_login_token' => 'orphaned-login-token',
        ]);
        DB::table('sessions')->insert([
            'id' => 'orphaned-session',
            'user_id' => $administrator->getKey(),
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test',
            'payload' => base64_encode(json_encode(['_token' => 'session-token'])),
            'last_activity' => now()->timestamp,
        ]);

        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('admin_login_token');

        $this->assertNotSame('orphaned-login-token', $administrator->fresh()->active_login_token);
    }

    public function test_admin_session_that_still_contains_its_token_cannot_be_replaced(): void
    {
        config(['session.driver' => 'database']);

        $administrator = User::factory()->create([
            'nip' => '1234567890',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $activeToken = $administrator->fresh()->active_login_token;

        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertSessionHasErrors('nip');

        $this->assertSame($activeToken, $administrator->fresh()->active_login_token);
    }
}
