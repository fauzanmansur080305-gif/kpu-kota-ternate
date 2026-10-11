<?php

namespace Tests\Feature;

use App\Admin\GuestWorkbookExporter;
use App\Models\Tamu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class AdminGuestExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_prompts_before_xlsx_export_and_includes_guest_images_in_the_workbook(): void
    {
        Storage::fake('local');
        $administrator = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/bUEAAAAASUVORK5CYII=', true);
        Storage::disk('local')->put('tamus/dokumentasi/dokumentasi.png', $png);

        $tamu = Tamu::create([
            'hari_tanggal' => '2026-10-04',
            'jam' => '10:30:00',
            'nama' => 'Tamu Laporan',
            'instansi' => 'Instansi Uji',
            'alamat' => 'Alamat Uji',
            'keperluan' => 'Keperluan Uji',
            'tujuan' => 'Ketua KPU',
            'no_handphone' => '081234567890',
            'paraf' => 'data:image/png;base64,'.base64_encode($png),
            'dokumentasi' => 'tamus/dokumentasi/dokumentasi.png',
        ]);

        $this->get(route('admin.laporan.index'))
            ->assertOk()
            ->assertSee('Ekspor data tamu')
            ->assertSee(route('admin.laporan.export.tamu'))
            ->assertSee('Pilih bulan')
            ->assertSee("destination.pathname.includes('/export/')", false);

        $download = $this->get(route('admin.laporan.export.tamu'));
        $download->assertDownload('laporan-data-tamu-'.now('Asia/Jayapura')->format('Y-m-d').'.xlsx');
        $downloadPath = $download->baseResponse->getFile()->getPathname();
        if (is_file($downloadPath)) {
            unlink($downloadPath);
        }

        $file = app(GuestWorkbookExporter::class)->export([$tamu]);
        $archive = new ZipArchive;

        try {
            $this->assertSame(true, $archive->open($file));
            $this->assertNotFalse($archive->getFromName('xl/worksheets/sheet1.xml'));
            $this->assertNotFalse($archive->getFromName('xl/drawings/drawing1.xml'));
            $this->assertNotFalse($archive->getFromName('xl/media/image1.png'));
            $this->assertNotFalse($archive->getFromName('xl/media/image2.png'));
            $this->assertStringContainsString('Tamu Laporan', $archive->getFromName('xl/worksheets/sheet1.xml'));
            $this->assertStringContainsString('Dokumentasi', $archive->getFromName('xl/worksheets/sheet1.xml'));
            $this->assertStringContainsString('J3', $archive->getFromName('xl/worksheets/sheet1.xml'));
            $this->assertStringContainsString('Terlampir', $archive->getFromName('xl/worksheets/sheet1.xml'));
            preg_match('/<c r="I3"[^>]*>.*?<\/c>/', $archive->getFromName('xl/worksheets/sheet1.xml'), $signatureCell);
            $this->assertNotEmpty($signatureCell);
            $this->assertStringNotContainsString('Terlampir', $signatureCell[0]);
            $this->assertStringContainsString('Terlampir', $archive->getFromName('xl/worksheets/sheet1.xml'));
            $this->assertStringContainsString('twoCellAnchor', $archive->getFromName('xl/drawings/drawing1.xml'));
            $this->assertStringContainsString('<xdr:col>9</xdr:col>', $archive->getFromName('xl/drawings/drawing1.xml'));
            $this->assertXmlPartsAreValid($archive);
        } finally {
            $archive->close();
            unlink($file);
        }
    }

    public function test_export_can_be_limited_to_a_selected_month(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->createGuest('2026-09-30', 'Tamu Bulan Dipilih');
        $this->createGuest('2026-10-01', 'Tamu Bulan Lain');

        $download = $this->get(route('admin.laporan.export.tamu', [
            'jenis' => 'bulan',
            'bulan' => '2026-09',
        ]));

        $download->assertDownload('laporan-data-tamu-2026-09.xlsx');
        $downloadPath = $download->baseResponse->getFile()->getPathname();
        $archive = new ZipArchive;

        try {
            $this->assertSame(true, $archive->open($downloadPath));
            $worksheet = $archive->getFromName('xl/worksheets/sheet1.xml');
            $this->assertIsString($worksheet);
            $this->assertStringContainsString('Tamu Bulan Dipilih', $worksheet);
            $this->assertStringNotContainsString('Tamu Bulan Lain', $worksheet);
        } finally {
            $archive->close();
            if (is_file($downloadPath)) {
                unlink($downloadPath);
            }
        }
    }

    public function test_export_rejects_an_invalid_month(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->from(route('admin.laporan.index'))
            ->get(route('admin.laporan.export.tamu', [
                'jenis' => 'bulan',
                'bulan' => '2026-13',
            ]))
            ->assertRedirect(route('admin.laporan.index'))
            ->assertSessionHasErrors('bulan');
    }

    public function test_legacy_guest_image_paths_are_exported_from_the_gambar_column(): void
    {
        Storage::fake('local');
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        Storage::disk('local')->put('tamus/dokumentasi/legacy.png', $this->png());
        $this->createGuest('2026-10-04', 'Dokumentasi Lama', [
            'gambar' => 'tamus/dokumentasi/legacy.png',
            'dokumentasi' => null,
        ]);

        $file = app(GuestWorkbookExporter::class)->export(Tamu::query()->get());
        $archive = new ZipArchive;

        try {
            $this->assertSame(true, $archive->open($file));
            $this->assertNotFalse($archive->getFromName('xl/media/image1.png'));
            $worksheet = $archive->getFromName('xl/worksheets/sheet1.xml');
            $this->assertIsString($worksheet);
            $this->assertStringContainsString('Terlampir', $worksheet);
        } finally {
            $archive->close();
            unlink($file);
        }
    }

    private function createGuest(string $date, string $name, array $media = []): Tamu
    {
        return Tamu::create(array_replace([
            'hari_tanggal' => $date,
            'jam' => '10:30:00',
            'nama' => $name,
            'instansi' => 'Instansi Uji',
            'alamat' => 'Alamat Uji',
            'keperluan' => 'Keperluan Uji',
            'tujuan' => 'Ketua KPU',
            'no_handphone' => '081234567890',
            'paraf' => null,
        ], $media));
    }

    private function png(): string
    {
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/bUEAAAAASUVORK5CYII=', true);
    }

    private function assertXmlPartsAreValid(ZipArchive $archive): void
    {
        for ($index = 0; $index < $archive->numFiles; $index++) {
            $name = $archive->getNameIndex($index);

            if (! is_string($name) || ! preg_match('/\.(xml|rels)$/', $name)) {
                continue;
            }

            $document = new \DOMDocument;
            $this->assertTrue($document->loadXML($archive->getFromIndex($index)), "{$name} must contain valid XML.");
        }
    }
}
