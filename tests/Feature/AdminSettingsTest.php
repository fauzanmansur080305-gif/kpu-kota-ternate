<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSee('1234567890');

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

    private function authenticateAdmin(User $administrator): void
    {
        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }
}
