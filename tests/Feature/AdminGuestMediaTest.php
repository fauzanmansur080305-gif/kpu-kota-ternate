<?php

namespace Tests\Feature;

use App\Models\Tamu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminGuestMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrator_can_upload_view_update_and_delete_guest_images_and_drawn_signatures(): void
    {
        Storage::fake('local');

        $administrator = User::factory()->create([
            'role' => 'admin',
        ]);
        $signature = $this->signature();
        $this->authenticateAdmin($administrator);

        $this->post(route('admin.tamu.store'), $this->guestData($signature) + [
            'gambar' => UploadedFile::fake()->createWithContent('dokumentasi.png', $this->png()),
        ])
            ->assertRedirect(route('admin.tamu.index'))
            ->assertSessionHas('success', 'Data tamu berhasil ditambahkan.');

        $tamu = Tamu::query()->firstOrFail();

        $this->assertNotEmpty($tamu->gambar);
        $this->assertSame($tamu->gambar, $tamu->dokumentasi);
        Storage::disk('local')->assertExists($tamu->gambar);

        $this->get(route('admin.tamu.index'))
            ->assertOk()
            ->assertSee(route('admin.tamu.gambar', $tamu))
            ->assertSee(route('admin.tamu.paraf', $tamu))
            ->assertSee('data-gambar-url="'.route('admin.tamu.gambar', $tamu).'"', false)
            ->assertSee('createSignatureCanvas')
            ->assertDontSee('Absensi');

        $this->get(route('admin.tamu.edit', $tamu))
            ->assertOk()
            ->assertSee(route('admin.tamu.gambar', $tamu))
            ->assertSee('Gambar dokumentasi saat ini')
            ->assertSee('editSignatureCanvas');

        $this->get(route('admin.tamu.show', $tamu))
            ->assertOk()
            ->assertSee(route('admin.tamu.paraf', $tamu))
            ->assertSee(route('admin.tamu.gambar', $tamu));

        $this->get(route('admin.tamu.gambar', $tamu))
            ->assertOk()
            ->assertHeader('content-type', 'image/png');

        $this->get(route('admin.tamu.paraf', $tamu))
            ->assertOk()
            ->assertContent(base64_decode(substr($signature, strpos($signature, ',') + 1)));

        $gambarLama = $tamu->gambar;
        $this->put(route('admin.tamu.update', $tamu), $this->guestData($signature, 'Nama Diperbarui') + [
            'gambar' => UploadedFile::fake()->createWithContent('dokumentasi-baru.png', $this->png()),
        ])
            ->assertRedirect(route('admin.tamu.index'))
            ->assertSessionHas('success', 'Data tamu berhasil diperbarui.');

        $tamu->refresh();
        $this->assertNotSame($gambarLama, $tamu->gambar);
        Storage::disk('local')->assertMissing($gambarLama);
        Storage::disk('local')->assertExists($tamu->gambar);

        $this->put(route('admin.tamu.update', $tamu), $this->guestData($signature, 'Nama Tetap'))
            ->assertRedirect(route('admin.tamu.index'));
        $tamu->refresh();
        Storage::disk('local')->assertExists($tamu->gambar);

        $this->delete(route('admin.tamu.destroy', $tamu))
            ->assertRedirect(route('admin.tamu.index'))
            ->assertSessionHas('success', 'Data tamu berhasil dihapus.');

        Storage::disk('local')->assertMissing($tamu->gambar);
        $this->assertDatabaseMissing('tamus', ['id' => $tamu->id]);
    }

    public function test_guest_image_and_signature_routes_require_an_active_admin_session(): void
    {
        $tamu = Tamu::create($this->guestData($this->signature()));

        $this->get(route('admin.tamu.gambar', $tamu))->assertRedirect(route('login'));
        $this->get(route('admin.tamu.paraf', $tamu))->assertRedirect(route('login'));
    }

    public function test_administrator_can_delete_guest_data_in_an_inclusive_date_range(): void
    {
        Storage::fake('local');
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->authenticateAdmin($administrator);

        Storage::disk('local')->put('tamus/dokumentasi/sebelum.png', $this->png());
        Storage::disk('local')->put('tamus/dokumentasi/dalam.png', $this->png());
        Storage::disk('local')->put('tamus/dokumentasi/sesudah.png', $this->png());

        $before = Tamu::create(array_replace($this->guestData($this->signature()), [
            'hari_tanggal' => '2026-09-30',
            'gambar' => 'tamus/dokumentasi/sebelum.png',
        ]));
        $within = Tamu::create(array_replace($this->guestData($this->signature()), [
            'hari_tanggal' => '2026-10-01',
            'gambar' => 'tamus/dokumentasi/dalam.png',
        ]));
        $after = Tamu::create(array_replace($this->guestData($this->signature()), [
            'hari_tanggal' => '2026-10-03',
            'gambar' => 'tamus/dokumentasi/sesudah.png',
        ]));

        $this->delete(route('admin.tamu.destroy-matching'), [
            'target' => 'range',
            'date_from' => '2026-10-01',
            'date_to' => '2026-10-02',
        ])->assertRedirect(route('admin.tamu.index'))
            ->assertSessionHas('success', 'Data tamu pada rentang tanggal berhasil dihapus.');

        $this->assertDatabaseHas('tamus', ['id' => $before->id]);
        $this->assertDatabaseMissing('tamus', ['id' => $within->id]);
        $this->assertDatabaseHas('tamus', ['id' => $after->id]);
        Storage::disk('local')->assertExists('tamus/dokumentasi/sebelum.png');
        Storage::disk('local')->assertMissing('tamus/dokumentasi/dalam.png');
        Storage::disk('local')->assertExists('tamus/dokumentasi/sesudah.png');
    }

    public function test_administrator_must_confirm_before_deleting_all_guest_data(): void
    {
        Storage::fake('local');
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->authenticateAdmin($administrator);

        Storage::disk('local')->put('tamus/dokumentasi/semua.png', $this->png());
        $tamu = Tamu::create($this->guestData($this->signature()) + [
            'gambar' => 'tamus/dokumentasi/semua.png',
        ]);

        $this->delete(route('admin.tamu.destroy-matching'), [
            'target' => 'all',
        ])->assertSessionHasErrors('confirm_all');

        $this->assertDatabaseHas('tamus', ['id' => $tamu->id]);
        Storage::disk('local')->assertExists('tamus/dokumentasi/semua.png');

        $this->delete(route('admin.tamu.destroy-matching'), [
            'target' => 'all',
            'confirm_all' => '1',
        ])->assertRedirect(route('admin.tamu.index'))
            ->assertSessionHas('success', 'Semua data tamu berhasil dihapus.');

        $this->assertDatabaseMissing('tamus', ['id' => $tamu->id]);
        Storage::disk('local')->assertMissing('tamus/dokumentasi/semua.png');
    }

    /**
     * @return array<string, string>
     */
    private function guestData(string $signature, string $name = 'Tamu Uji'): array
    {
        return [
            'hari_tanggal' => '2026-10-04',
            'jam' => '10:30',
            'nama' => $name,
            'instansi' => 'Instansi Uji',
            'alamat' => 'Alamat Uji',
            'keperluan' => 'Keperluan Uji',
            'tujuan' => 'Ketua KPU',
            'no_handphone' => '081234567890',
            'paraf' => $signature,
        ];
    }

    private function signature(): string
    {
        return 'data:image/png;base64,'.base64_encode($this->png());
    }

    private function png(): string
    {
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/bUEAAAAASUVORK5CYII=', true);
    }

    private function authenticateAdmin(User $administrator): void
    {
        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }
}
