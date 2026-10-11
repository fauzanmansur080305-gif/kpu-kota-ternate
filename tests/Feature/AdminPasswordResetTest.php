<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\AdminPasswordResetCodeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AdminPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['cache.default' => 'array']);
        config(['mail.default' => 'smtp']);
        RateLimiter::clear('admin-password-reset-code:'.hash('sha256', 'admin@example.test'));
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_login_and_forgot_password_pages_link_to_admin_email_recovery(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee(route('password.request'));

        $this->get(route('password.request'))
            ->assertOk()
            ->assertSee('Email Administrator')
            ->assertSee(route('password.email'))
            ->assertSee('kode verifikasi 6 digit');
    }

    public function test_six_digit_code_is_sent_only_to_a_registered_administrator_email(): void
    {
        Notification::fake();

        $administrator = User::factory()->create([
            'email' => 'admin@example.test',
            'role' => 'admin',
        ]);
        $member = User::factory()->create([
            'email' => 'member@example.test',
            'role' => 'anggota',
        ]);

        $this->post(route('password.email'), ['email' => $administrator->email])
            ->assertRedirect(route('password.reset', ['email' => $administrator->email]))
            ->assertSessionHas('status');

        $notification = Notification::sent($administrator, AdminPasswordResetCodeNotification::class)->first();
        $this->assertInstanceOf(AdminPasswordResetCodeNotification::class, $notification);
        $this->assertMatchesRegularExpression('/^\d{6}$/', $notification->code);

        $reset = DB::table('password_reset_tokens')->where('email', $administrator->email)->first();
        $this->assertNotSame($notification->code, $reset->token);
        $this->assertTrue(Hash::check($notification->code, $reset->token));

        $this->post(route('password.email'), ['email' => $member->email])
            ->assertRedirect(route('password.reset', ['email' => $member->email]))
            ->assertSessionHas('status');

        Notification::assertNotSentTo($member, AdminPasswordResetCodeNotification::class);
    }

    public function test_code_request_reports_when_mail_delivery_is_not_configured(): void
    {
        Notification::fake();
        config(['mail.default' => 'log']);

        $administrator = User::factory()->create([
            'email' => 'admin@example.test',
            'role' => 'admin',
        ]);

        $this->from(route('password.request'))
            ->post(route('password.email'), ['email' => $administrator->email])
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('email');

        Notification::assertNotSentTo($administrator, AdminPasswordResetCodeNotification::class);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $administrator->email]);
    }

    public function test_administrator_can_login_with_the_email_code_and_update_password_from_settings(): void
    {
        Notification::fake();

        $administrator = User::factory()->create([
            'email' => 'admin@example.test',
            'role' => 'admin',
            'password' => 'old-password',
            'active_login_token' => 'active-session-token',
        ]);

        $this->post(route('password.email'), ['email' => $administrator->email])
            ->assertRedirect(route('password.reset', ['email' => $administrator->email]));

        $notification = Notification::sent($administrator, AdminPasswordResetCodeNotification::class)->first();

        $this->get(route('password.reset', ['email' => $administrator->email]))
            ->assertOk()
            ->assertSee('Kode Verifikasi 6 Digit')
            ->assertSee(route('password.verify'))
            ->assertDontSee('name="password"', false);

        $this->post(route('password.verify'), [
            'code' => $notification->code,
            'email' => $administrator->email,
        ])
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('password_recovery_verified_at')
            ->assertSessionHas('password_recovery_notice');

        $administrator->refresh();
        $this->assertTrue(Hash::check('old-password', $administrator->password));
        $this->assertNotNull($administrator->active_login_token);
        $this->assertNull($administrator->admin_login_blocked_until);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $administrator->email]);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Silakan perbarui kata sandi Anda di menu Pengaturan dalam 30 detik.')
            ->assertSee('data-auto-dismiss="30000"', false);

        $this->get(route('admin.pengaturan.index'))
            ->assertOk()
            ->assertSee('Masukkan kata sandi baru minimal 8 karakter.')
            ->assertDontSee('name="current_password"', false);

        $this->post(route('admin.pengaturan.password'), [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
            ->assertRedirect(route('admin.pengaturan.index'))
            ->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-password', $administrator->fresh()->password));
        $this->assertFalse(session()->has('password_recovery_verified_at'));
    }

    public function test_invalid_code_does_not_authenticate_administrator_and_is_limited(): void
    {
        Notification::fake();

        $administrator = User::factory()->create([
            'email' => 'admin@example.test',
            'role' => 'admin',
            'password' => 'old-password',
        ]);

        $this->post(route('password.email'), ['email' => $administrator->email]);
        $notification = Notification::sent($administrator, AdminPasswordResetCodeNotification::class)->first();
        $invalidCode = $notification->code === '000000' ? '000001' : '000000';

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->from(route('password.reset', ['email' => $administrator->email]))
                ->post(route('password.verify'), [
                    'code' => $invalidCode,
                    'email' => $administrator->email,
                ])
                ->assertSessionHasErrors('code');
        }

        $this->from(route('password.reset', ['email' => $administrator->email]))
            ->post(route('password.verify'), [
                'code' => $invalidCode,
                'email' => $administrator->email,
            ])
            ->assertSessionHasErrors('code');

        $this->assertTrue(Hash::check('old-password', $administrator->fresh()->password));
        $this->assertGuest();
    }

    public function test_expired_code_does_not_authenticate_administrator(): void
    {
        Notification::fake();

        $administrator = User::factory()->create([
            'email' => 'admin@example.test',
            'role' => 'admin',
            'password' => 'old-password',
        ]);

        $this->post(route('password.email'), ['email' => $administrator->email]);
        $notification = Notification::sent($administrator, AdminPasswordResetCodeNotification::class)->first();
        DB::table('password_reset_tokens')
            ->where('email', $administrator->email)
            ->update(['created_at' => now()->subMinutes(11)]);

        $this->from(route('password.reset', ['email' => $administrator->email]))
            ->post(route('password.verify'), [
                'code' => $notification->code,
                'email' => $administrator->email,
            ])
            ->assertSessionHasErrors('code');

        $this->assertTrue(Hash::check('old-password', $administrator->fresh()->password));
        $this->assertGuest();
    }
}
