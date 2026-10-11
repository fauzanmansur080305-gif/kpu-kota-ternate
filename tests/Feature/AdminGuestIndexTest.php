<?php

namespace Tests\Feature;

use App\Models\Tamu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminGuestIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_table_defaults_to_all_visitors_and_can_filter_to_today(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $today = now('Asia/Jayapura')->toDateString();
        $yesterday = now('Asia/Jayapura')->subDay()->toDateString();

        $this->createGuest($today, 'Tamu Hari Ini');
        $this->createGuest($yesterday, 'Tamu Sebelumnya');

        $this->authenticateAdmin($administrator);

        $this->get(route('admin.tamu.index'))
            ->assertOk()
            ->assertSee('Daftar Total Tamu')
            ->assertSee('text-decoration: none;', false)
            ->assertSee('Tamu Hari Ini')
            ->assertSee('Tamu Sebelumnya');

        $this->get(route('admin.tamu.index', ['filter' => 'today']))
            ->assertOk()
            ->assertSee('Daftar Tamu Hari Ini')
            ->assertSee('Tamu Hari Ini')
            ->assertDontSee('Tamu Sebelumnya');
    }

    public function test_guest_table_search_matches_guest_details_and_respects_the_active_filter(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $today = now('Asia/Jayapura')->toDateString();
        $yesterday = now('Asia/Jayapura')->subDay()->toDateString();

        $this->createGuest($today, 'Tamu Pencarian Hari Ini');
        $this->createGuest($yesterday, 'Tamu Pencarian Sebelumnya');
        $this->authenticateAdmin($administrator);

        $this->get(route('admin.tamu.index', ['filter' => 'all', 'q' => 'Instansi Uji']))
            ->assertOk()
            ->assertSee('Tamu Pencarian Hari Ini')
            ->assertSee('Tamu Pencarian Sebelumnya')
            ->assertSee('2</strong> hasil untuk pencarian', false)
            ->assertSee('name="q"', false);

        $this->get(route('admin.tamu.index', ['filter' => 'today', 'q' => 'Instansi Uji']))
            ->assertOk()
            ->assertSee('Tamu Pencarian Hari Ini')
            ->assertDontSee('Tamu Pencarian Sebelumnya')
            ->assertSee('1</strong> hasil untuk pencarian', false);

        $this->get(route('admin.tamu.index', ['filter' => 'today', 'q' => 'tidak ditemukan']))
            ->assertOk()
            ->assertSee('Tidak ada hasil pencarian')
            ->assertSee('Hapus pencarian');
    }

    public function test_live_guest_search_returns_a_filtered_table_fragment_for_ajax_requests(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $today = now('Asia/Jayapura')->toDateString();

        $this->createGuest($today, 'Tamu Langsung Hari Ini');
        $this->createGuest(now('Asia/Jayapura')->subDay()->toDateString(), 'Tamu Lama');
        $this->authenticateAdmin($administrator);

        $response = $this->getJson(route('admin.tamu.index', [
            'filter' => 'today',
            'q' => 'Langsung',
        ]))->assertOk()->assertJsonStructure(['html', 'currentPage', 'lastPage']);

        $html = $response->json('html');
        $this->assertStringContainsString('Tamu Langsung Hari Ini', $html);
        $this->assertStringNotContainsString('Tamu Lama', $html);
        $this->assertStringContainsString('data-modal="edit"', $html);
    }

    public function test_success_notice_is_displayed_with_a_thirty_second_auto_dismiss(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->authenticateAdmin($administrator);

        $this->withSession(['success' => 'Data tamu berhasil ditambahkan.'])
            ->get(route('admin.tamu.index'))
            ->assertOk()
            ->assertSee('Data tamu berhasil ditambahkan.')
            ->assertSee('data-auto-dismiss="30000"', false);
    }

    public function test_dashboard_stat_cards_link_to_the_matching_guest_filters(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $this->authenticateAdmin($administrator);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Admin')
            ->assertSee(route('admin.tamu.index', ['filter' => 'all']))
            ->assertSee(route('admin.tamu.index', ['filter' => 'today']))
            ->assertSee('dashboardClock')
            ->assertSee('Waktu Administrator')
            ->assertSee('jayapuraWeekdays')
            ->assertSee('getUTCHours()')
            ->assertSee('window.setInterval(updateDashboardClock, 1000)', false)
            ->assertSee('Yakin ingin');
    }

    public function test_admin_primary_pages_share_the_same_navigation_and_account_header(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);
        $this->authenticateAdmin($administrator);

        foreach ([
            route('admin.dashboard'),
            route('admin.tamu.index'),
            route('admin.laporan.index'),
            route('admin.pengaturan.index'),
        ] as $url) {
            $this->get($url)
                ->assertOk()
                ->assertSee('admin-shell-sidebar', false)
                ->assertSee('Dashboard Admin')
                ->assertSee('Administrator')
                ->assertSee(route('admin.tamu.index'));
        }
    }

    public function test_report_uses_the_admin_header_and_logout_confirmation(): void
    {
        $administrator = User::factory()->create(['role' => 'admin']);

        $this->authenticateAdmin($administrator);

        $this->get(route('admin.laporan.index'))
            ->assertOk()
            ->assertSee('Dashboard Admin')
            ->assertSee('Laporan Administrator')
            ->assertSee('Administrator')
            ->assertSee('Yakin ingin');
    }

    private function createGuest(string $date, string $name): void
    {
        Tamu::create([
            'hari_tanggal' => $date,
            'jam' => '09:30:00',
            'nama' => $name,
            'instansi' => 'Instansi Uji',
            'alamat' => 'Alamat Uji',
            'keperluan' => 'Keperluan Uji',
            'tujuan' => 'Ketua KPU',
            'no_handphone' => '081234567890',
            'paraf' => null,
        ]);
    }

    private function authenticateAdmin(User $administrator): void
    {
        $this->post(route('login.store'), [
            'nip' => $administrator->nip,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }
}
