<?php

namespace Tests\Feature;

use App\Models\Tamu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GuestBookPhotoTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_clock_runs_even_when_guest_form_controls_are_not_rendered(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="currentDate"', false)
            ->assertSee('id="currentTime"', false)
            ->assertSee('if (guestPhoto && guestCameraPhoto && guestPhotoSelection && guestCameraDialog && guestCameraPreview && guestCameraCanvas)', false)
            ->assertSee('updateDateTime();', false)
            ->assertSee('setInterval(updateDateTime, 1000)', false);
    }

    public function test_public_guest_form_offers_camera_capture_and_file_selection(): void
    {
        $this->get(route('tamu.index'))
            ->assertOk()
            ->assertSee('name="dokumentasi"', false)
            ->assertSee('id="openGuestCamera"', false)
            ->assertSee('id="chooseGuestPhoto"', false)
            ->assertSee('navigator.mediaDevices.getUserMedia', false)
            ->assertSee('capture="environment"', false)
            ->assertSee('foto akan disimpan bersama data kunjungan.', false)
            ->assertSee('id="kpuLoading"', false)
            ->assertSee('window.kpuLoading.show();', false)
            ->assertSee('window.kpuLoading.hide();', false)
            ->assertSee('src="'.asset('kpu-logo.png').'"', false);
    }

    public function test_login_page_uses_the_kpu_application_icon(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('type="image/png" href="'.asset('kpu-logo.png').'?v=20261012"', false);
    }

    public function test_public_guest_form_saves_optional_documentation_photos(): void
    {
        Storage::fake('local');

        $this->postJson(route('tamu.store'), [
            'nama' => 'Tamu Uji',
            'instansi' => 'Instansi Uji',
            'alamat' => 'Alamat Uji',
            'keperluan' => 'Keperluan Uji',
            'tujuan' => 'Ketua KPU',
            'no_handphone' => '081234567890',
            'paraf' => 'data:image/png;base64,dGVzdA==',
            'dokumentasi' => UploadedFile::fake()->createWithContent('foto-tamu.png', $this->png()),
        ])->assertOk()
            ->assertJson(['message' => 'Data kunjungan berhasil disimpan.']);

        $tamu = Tamu::query()->firstOrFail();

        $this->assertNotEmpty($tamu->dokumentasi);
        $this->assertSame($tamu->dokumentasi, $tamu->gambar);
        Storage::disk('local')->assertExists($tamu->dokumentasi);
    }

    public function test_public_guest_form_still_accepts_submissions_without_a_photo(): void
    {
        Storage::fake('local');

        $this->postJson(route('tamu.store'), [
            'nama' => 'Tamu Tanpa Foto',
            'instansi' => 'Instansi Uji',
            'alamat' => 'Alamat Uji',
            'keperluan' => 'Keperluan Uji',
            'tujuan' => 'Ketua KPU',
            'no_handphone' => '081234567890',
            'paraf' => 'data:image/png;base64,dGVzdA==',
        ])->assertOk()
            ->assertJson(['message' => 'Data kunjungan berhasil disimpan.']);

        $this->assertNull(Tamu::query()->firstOrFail()->dokumentasi);
    }

    private function png(): string
    {
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/bUEAAAAASUVORK5CYII=', true);
    }
}
