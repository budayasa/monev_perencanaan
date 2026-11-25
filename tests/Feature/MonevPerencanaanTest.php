<?php

namespace Tests\Feature;

use App\Models\JadwalMonev;
use App\Models\MonevRealisasi;
use App\Models\PerangkatDaerah;
use App\Models\Renja;
use App\Models\Renstra;
use App\Models\Rkpd;
use App\Models\Rpjmd;
use App\Models\RpjmdIndikatorSasaran;
use App\Models\RpjmdIndikatorTujuan;
use App\Models\RpjmdMisi;
use App\Models\RpjmdProgram;
use App\Models\RpjmdSasaran;
use App\Models\RpjmdTujuan;
use App\Models\RpjmdVisi;
use App\Models\User;
use App\Services\AiMonevService;
use App\Services\MonevService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonevPerencanaanTest extends TestCase
{
    use RefreshDatabase;

    public function test_predikat_permendagri_86_2017_calculations(): void
    {
        $this->assertSame('Sangat Tinggi', MonevService::getPredikatPermendagri(92.5)['label']);
        $this->assertSame('Tinggi', MonevService::getPredikatPermendagri(85.0)['label']);
        $this->assertSame('Sedang', MonevService::getPredikatPermendagri(70.0)['label']);
        $this->assertSame('Rendah', MonevService::getPredikatPermendagri(58.0)['label']);
        $this->assertSame('Sangat Rendah', MonevService::getPredikatPermendagri(45.0)['label']);
    }

    public function test_seeder_populates_37_opd_cascading_documents_and_monev_realisasi(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(37, PerangkatDaerah::count());
        $this->assertGreaterThanOrEqual(40, User::count());
        $this->assertSame(5, Rpjmd::count());
        $this->assertSame(5, Rkpd::count());
        $this->assertSame(74, Renstra::count());
        $this->assertSame(148, Renja::count());
        $this->assertSame(148, MonevRealisasi::count());
        $this->assertTrue(JadwalMonev::isBuka('2026', 1));

        $ikuNonAkumulatif = Rpjmd::where('kode_iku', 'IKU-KAB-01')->firstOrFail();
        $this->assertSame('non_akumulatif', $ikuNonAkumulatif->sifat_target);
        $this->assertEquals(4.60, $ikuNonAkumulatif->target_akhir);
        $this->assertGreaterThan(1, $ikuNonAkumulatif->opds()->count());

        $ikuAkumulatif = Rpjmd::where('kode_iku', 'IKU-KAB-05')->firstOrFail();
        $this->assertSame('akumulatif', $ikuAkumulatif->sifat_target);
        $this->assertEquals(80.00, $ikuAkumulatif->target_akhir);
        $this->assertGreaterThan(1, $ikuAkumulatif->opds()->count());

        // Verifikasi Hierarki RPJMD baru: Visi (1) -> Misi -> Tujuan + Indikator Tujuan -> Sasaran + Indikator Sasaran -> Program (1 Indikator + Pagu 5 Tahun)
        $this->assertSame(1, RpjmdVisi::count());
        $this->assertSame(3, RpjmdMisi::count());
        $this->assertSame(3, RpjmdTujuan::count());
        $this->assertSame(6, RpjmdIndikatorTujuan::count());
        $this->assertSame(3, RpjmdSasaran::count());
        $this->assertSame(6, RpjmdIndikatorSasaran::count());
        $this->assertSame(6, RpjmdProgram::count());

        $progAkumulatif = RpjmdProgram::where('kode_program', '1.03.10')->firstOrFail();
        $this->assertEquals(210.0, $progAkumulatif->target_akhir); // 35+40+42+45+48
        $this->assertEquals(1_700_000_000_000, $progAkumulatif->pagu_5_tahun);
        $this->assertSame(2, $progAkumulatif->opds()->count());

        // Verifikasi Hierarki RPJMD baru: Visi (1) -> Misi -> Tujuan + Indikator Tujuan -> Sasaran + Indikator Sasaran -> Program (1 Indikator + Pagu 5 Tahun)
        $this->assertSame(1, RpjmdVisi::count());
        $this->assertSame(3, RpjmdMisi::count());
        $this->assertSame(3, RpjmdTujuan::count());
        $this->assertSame(6, RpjmdIndikatorTujuan::count());
        $this->assertSame(3, RpjmdSasaran::count());
        $this->assertSame(6, RpjmdIndikatorSasaran::count());
        $this->assertSame(6, RpjmdProgram::count());

        $progAkumulatif = RpjmdProgram::where('kode_program', '1.03.10')->firstOrFail();
        $this->assertEquals(210.0, $progAkumulatif->target_akhir); // 35+40+42+45+48
        $this->assertEquals(1_700_000_000_000, $progAkumulatif->pagu_5_tahun);
        $this->assertSame(2, $progAkumulatif->opds()->count());
    }

    public function test_dashboard_and_pdf_print_routes_work_for_authenticated_roles(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::where('role', 'admin')->firstOrFail();

        $this->actingAs($admin)
            ->withSession(['UserIsAuthenticated' => 1])
            ->get('/admin/dashboard')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->withSession(['UserIsAuthenticated' => 1])
            ->get('/admin/rpjmds')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->withSession(['UserIsAuthenticated' => 1])
            ->get('/admin/satuans')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->withSession(['UserIsAuthenticated' => 1])
            ->get('/admin/master-nomenklatur')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->withSession(['UserIsAuthenticated' => 1])
            ->get('/admin/lembar-monev-triwulan')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->withSession(['UserIsAuthenticated' => 1])
            ->get('/admin/laporan-hasil-evaluasi')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->withSession(['UserIsAuthenticated' => 1])
            ->get('/cetak-evaluasi-pdf/1/2026/1')
            ->assertStatus(200);

        $aiService = new AiMonevService;
        $stats = MonevService::getStatistikKabupaten('2026', 1);
        $summary = $aiService->generateExecutiveSummaryKabupaten($stats, [], [], [], '2026', 1);
        $this->assertArrayHasKey('ringkasan_utama', $summary);
    }
}
