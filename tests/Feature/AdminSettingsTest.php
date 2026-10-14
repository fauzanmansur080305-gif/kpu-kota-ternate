<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_view_account_details_and_update_password(): void
    {
        $administrator = User::factory()->create([
            'name' => 'Admin Ternate',
            'email' => 'admin@example.test',
            'nip' => '1234567890',
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->authenticateAdmin($administrator);

        $this->get(route('admin.pengaturan.index'))
            ->assertOk()
            ->assertSee('Admin Ternate')
            ->assertSee('admin@example.test')
            ->assertSee('1234567890')
            ->assertSee('Reset Sistem')
            ->assertSee('Lanjutkan');

        $this->post(route('admin.pengaturan.password'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
            ->assertRedirect(route('admin.pengaturan.index'))
            ->assertSessionHas('success');

        $this->get(route('admin.pengaturan.index'))
            ->assertOk()
            ->assertSee('Kata sandi berhasil diperbarui.')
            ->assertSee('data-auto-dismiss="30000"', false);

        $this->assertTrue(Hash::check('new-password', $administrator->fresh()->password));
    }

    public function test_administrator_cannot_update_password_with_an_incorrect_current_password(): void
    {
        $administrator = User::factory()->create([
            'role' => 'admin',
            'password' => 'password',
        ]);

        $this->authenticateAdmin($administrator);

        $this->from(route('admin.pengaturan.index'))
            ->post(route('admin.pengaturan.password'), [
                'current_password' => 'incorrect-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('password', $administrator->fresh()->password));
    }

    public function test_non_administrators_cannot_access_admin_settings(): void
    {
        $user = User::factory()->create(['role' => 'anggota']);

        $this->actingAs($user)
            ->get(route('admin.pengaturan.index'))
            ->assertForbidden();
    }

    public function test_administrator_can_update_only_nip_and_email(): void
    {
        $administrator = User::factory()->create([
            'nip' => '1234567890',
            'email' => 'admin@example.test',
            'role' => 'admin',
        ]);
        $this->authenticateAdmin($administrator);

        $this->post(route('admin.pengaturan.profile'), [
            'nip' => '9876543210',
            'email' => 'updated@example.test',
            'name' => 'Nama Tidak Boleh Diubah',
        ])->assertRedirect(route('admin.pengaturan.index'))
            ->assertSessionHas('profile_success');

        $this->get(route('admin.pengaturan.index'))
            ->assertOk()
            ->assertSee('Data administrator berhasil diperbarui.')
            ->assertSee('data-auto-dismiss="30000"', false);

        $this->assertSame('9876543210', $administrator->fresh()->nip);
        $this->assertSame('updated@example.test', $administrator->fresh()->email);
        $this->assertNotSame('Nama Tidak Boleh Diubah', $administrator->fresh()->name);
    }

    public function test_administrator_can_reset_system_data_without_deleting_users_or_operational_data(): void
    {
        $administrator = User::factory()->create([
            'nip' => '1234567890',
            'role' => 'admin',
        ]);
        $this->authenticateAdmin($administrator);
        User::factory()->create(['role' => 'anggota']);

        DB::table('tamus')->insert([
            'hari_tanggal' => '2026-10-14',
            'jam' => '10:00:00',
            'nama' => 'Tamu Uji',
            'instansi' => 'Instansi Uji',
            'alamat' => 'Alamat Uji',
            'keperluan' => 'Keperluan Uji',
            'tujuan' => 'KPU',
            'no_handphone' => '081234567890',
        ]);
        DB::table('sessions')->insert([
            'id' => 'session-to-reset',
            'user_id' => $administrator->id,
            'payload' => 'session data',
            'last_activity' => now()->timestamp,
        ]);
        DB::table('password_reset_tokens')->insert([
            'email' => $administrator->email,
            'token' => 'reset-token',
            'created_at' => now(),
        ]);
        DB::table('cache')->insert([
            'key' => 'cache-to-reset',
            'value' => 'cached data',
            'expiration' => now()->addHour()->timestamp,
        ]);
        DB::table('cache_locks')->insert([
            'key' => 'lock-to-reset',
            'owner' => 'lock-owner',
            'expiration' => now()->addHour()->timestamp,
        ]);
        DB::table('jobs')->insert([
            'queue' => 'default',
            'payload' => '{}',
            'attempts' => 0,
            'available_at' => now()->timestamp,
            'created_at' => now()->timestamp,
        ]);

        $this->post(route('admin.pengaturan.reset-system'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Reset sistem berhasil. Seluruh sesi telah dihapus; silakan masuk kembali.');

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'id' => $administrator->id,
            'email' => $administrator->email,
            'role' => 'admin',
        ]);
        $this->assertDatabaseCount('tamus', 0);
        $this->assertDatabaseCount('sessions', 0);
        $this->assertDatabaseCount('password_reset_tokens', 0);
        $this->assertDatabaseCount('cache', 0);
        $this->assertDatabaseCount('cache_locks', 0);
        $this->assertDatabaseCount('jobs', 1);
    }

    public function test_non_administrators_cannot_reset_system_data(): void
    {
        $user = User::factory()->create(['role' => 'anggota']);

        $this->actingAs($user)
            ->post(route('admin.pengaturan.reset-system'))
            ->assertForbidden();
    }

    private function authenticateAdmin(User $administrator): void
    {
        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }
}
