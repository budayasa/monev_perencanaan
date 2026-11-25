<?php

namespace App\Services;

use App\Models\JadwalMonev;
use App\Models\MonevRealisasi;
use App\Models\PerangkatDaerah;
use App\Models\Renja;
use App\Models\Renstra;
use App\Models\Rkpd;
use App\Models\Rpjmd;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class MonevService
{
    /**
     * Hitung % Capaian Kinerja (Fisik).
     */
    public static function hitungPersenKinerja(float $realisasi, float $target): float
    {
        if ($target <= 0) {
            return 0.0;
        }

        return round(min(100.0, max(0.0, ($realisasi / $target) * 100)), 2);
    }

    /**
     * Hitung % Serapan Anggaran (Keuangan).
     */
    public static function hitungPersenAnggaran(float $realisasiRp, float $paguRp): float
    {
        if ($paguRp <= 0) {
            return 0.0;
        }

        return round(min(100.0, max(0.0, ($realisasiRp / $paguRp) * 100)), 2);
    }

    /**
     * Tentukan Predikat Kinerja sesuai Permendagri Nomor 86 Tahun 2017:
     * - Sangat Tinggi : > 90% (>= 90.01%)
     * - Tinggi        : 75.01% s/d 90% (> 75% && <= 90%)
     * - Sedang        : 65.01% s/d 75% (> 65% && <= 75%)
     * - Rendah        : 50.01% s/d 65% (> 50% && <= 65%)
     * - Sangat Rendah : <= 50%
     */
    public static function getPredikatPermendagri(float $persen): array
    {
        $val = round($persen, 2);

        if ($val > 90.0) {
            return [
                'kode' => 'ST',
                'label' => 'Sangat Tinggi',
                'rentang' => '> 90%',
                'color' => '#16a34a',
                'bg' => '#dcfce7',
                'border' => '#86efac',
                'badge_color' => 'success',
                'deskripsi' => 'Capaian kinerja sangat memuaskan sesuai target Permendagri 86/2017',
            ];
        }

        if ($val > 75.0) {
            return [
                'kode' => 'T',
                'label' => 'Tinggi',
                'rentang' => '75,01% - 90%',
                'color' => '#0284c7',
                'bg' => '#e0f2fe',
                'border' => '#7dd3fc',
                'badge_color' => 'info',
                'deskripsi' => 'Capaian kinerja tinggi dan berada pada jalur on-track',
            ];
        }

        if ($val > 65.0) {
            return [
                'kode' => 'S',
                'label' => 'Sedang',
                'rentang' => '65,01% - 75%',
                'color' => '#ca8a04',
                'bg' => '#fef9c3',
                'border' => '#fde047',
                'badge_color' => 'warning',
                'deskripsi' => 'Capaian kinerja cukup namun perlu akselerasi pelaksanaan',
            ];
        }

        if ($val > 50.0) {
            return [
                'kode' => 'R',
                'label' => 'Rendah',
                'rentang' => '50,01% - 65%',
                'color' => '#ea580c',
                'bg' => '#ffedd5',
                'border' => '#fdba74',
                'badge_color' => 'warning',
                'deskripsi' => 'Capaian kinerja rendah, butuh perhatian khusus & evaluasi hambatan',
            ];
        }

        return [
            'kode' => 'SR',
            'label' => 'Sangat Rendah',
            'rentang' => '<= 50%',
            'color' => '#dc2626',
            'bg' => '#fee2e2',
            'border' => '#fca5a5',
            'badge_color' => 'danger',
            'deskripsi' => 'Capaian kinerja sangat rendah, wajib intervensi & tindak lanjut segera',
        ];
    }

    /**
     * Format Rupiah ringkas / lengkap.
     */
    public static function formatRupiah(float $nominal, bool $singkat = false): string
    {
        if ($singkat) {
            if ($nominal >= 1_000_000_000_000) {
                return 'Rp '.number_format($nominal / 1_000_000_000_000, 2, ',', '.').' T';
            }
            if ($nominal >= 1_000_000_000) {
                return 'Rp '.number_format($nominal / 1_000_000_000, 2, ',', '.').' M';
            }
            if ($nominal >= 1_000_000) {
                return 'Rp '.number_format($nominal / 1_000_000, 1, ',', '.').' Jt';
            }
        }

        return 'Rp '.number_format($nominal, 0, ',', '.');
    }

    /**
     * Ambil Tahun & Triwulan aktif dari session atau JadwalMonev.
     */
    public static function getPeriodeAktif(): array
    {
        $jadwal = JadwalMonev::where('is_active', true)->orderByDesc('triwulan')->first();
        $tahunDefault = $jadwal?->tahun ?? '2026';
        $twDefault = $jadwal?->triwulan ?? 1;

        return [
            'tahun' => (string) session('tahun_aktif', $tahunDefault),
            'triwulan' => (int) session('triwulan_aktif', $twDefault),
        ];
    }

    /**
     * Bersihkan cache dashboard untuk tahun tertentu.
     */
    public static function clearCache(?string $tahun = null): void
    {
        $years = $tahun ? [$tahun] : ['2025', '2026', '2027'];
        foreach ($years as $yr) {
            for ($tw = 0; $tw <= 4; $tw++) {
                Cache::forget("monev_rekap_dashboard_{$yr}_tw{$tw}");
            }
        }
    }

    /**
     * Hitung rekapitulasi capaian Monev untuk 1 Perangkat Daerah pada Tahun & Triwulan tertentu.
     * Jika $triwulan = 0, ambil capaian tertinggi / kumulatif s/d TW terakhir yang terisi.
     */
    public static function hitungRekapOpd(
        PerangkatDaerah $pd,
        string $tahun = '2026',
        int $triwulan = 1,
        ?Collection $renjasPreloaded = null,
        ?Collection $realisasisPreloaded = null
    ): array {
        $renjas = $renjasPreloaded ?? Renja::where('perangkat_daerah_id', $pd->id)
            ->where('tahun', $tahun)
            ->get();

        $realisasis = $realisasisPreloaded ?? MonevRealisasi::where('perangkat_daerah_id', $pd->id)
            ->where('tahun', $tahun)
            ->when($triwulan > 0, fn ($q) => $q->where('triwulan', $triwulan))
            ->get();

        $totalSubKegiatan = $renjas->count();
        $totalProgram = $renjas->pluck('nama_program')->filter()->unique()->count();
        $totalKegiatan = $renjas->pluck('nama_kegiatan')->filter()->unique()->count();
        $totalPagu = (float) $renjas->sum('pagu_anggaran');

        $realisasiByRenja = $triwulan > 0
            ? $realisasis->keyBy('renja_id')
            : $realisasis->sortByDesc('triwulan')->groupBy('renja_id')->map(fn ($group) => $group->first());

        $totalRealisasiRp = 0.0;
        $sumPersenKinerja = 0.0;
        $countTerisi = 0;
        $countSubmitted = 0;
        $countVerified = 0;
        $countRevisi = 0;
        $countFinal = 0;

        foreach ($renjas as $renja) {
            /** @var MonevRealisasi|null $rel */
            $rel = $realisasiByRenja->get($renja->id);
            if ($rel) {
                $totalRealisasiRp += (float) $rel->realisasi_anggaran;
                $sumPersenKinerja += (float) $rel->persen_capaian_kinerja;

                if ($rel->realisasi_kinerja > 0 || $rel->realisasi_anggaran > 0 || $rel->status !== 'draft') {
                    $countTerisi++;
                }
                if (in_array($rel->status, ['submitted', 'verified', 'final'])) {
                    $countSubmitted++;
                }
                if (in_array($rel->status, ['verified', 'final'])) {
                    $countVerified++;
                }
                if ($rel->status === 'revisi') {
                    $countRevisi++;
                }
                if ($rel->status === 'final') {
                    $countFinal++;
                }
            }
        }

        $rataCapaianKinerja = $totalSubKegiatan > 0
            ? round($sumPersenKinerja / $totalSubKegiatan, 2)
            : 0.0;

        $persenSerapanAnggaran = self::hitungPersenAnggaran($totalRealisasiRp, $totalPagu);

        // Rata-rata Komposit (50% Fisik + 50% Keuangan) untuk peringkat kinerja komprehensif
        $nilaiKomposit = round(($rataCapaianKinerja * 0.5) + ($persenSerapanAnggaran * 0.5), 2);

        $progressInput = $totalSubKegiatan > 0
            ? round(($countTerisi / $totalSubKegiatan) * 100, 1)
            : 0.0;

        $progressVerifikasi = $totalSubKegiatan > 0
            ? round(($countVerified / $totalSubKegiatan) * 100, 1)
            : 0.0;

        $predikatKinerja = self::getPredikatPermendagri($rataCapaianKinerja);
        $predikatAnggaran = self::getPredikatPermendagri($persenSerapanAnggaran);
        $predikatKomposit = self::getPredikatPermendagri($nilaiKomposit);

        // Status Keseluruhan OPD
        $statusLaporan = 'Belum Mengisi';
        if ($totalSubKegiatan > 0) {
            if ($countFinal === $totalSubKegiatan) {
                $statusLaporan = 'Final (Terbit BA)';
            } elseif ($countVerified === $totalSubKegiatan) {
                $statusLaporan = 'Terverifikasi Bappeda';
            } elseif ($countRevisi > 0) {
                $statusLaporan = 'Perlu Revisi ('.$countRevisi.')';
            } elseif ($countSubmitted > 0) {
                $statusLaporan = 'Menunggu Verifikasi';
            } elseif ($countTerisi > 0) {
                $statusLaporan = 'Draft Pengisian';
            }
        }

        return [
            'id' => $pd->id,
            'kode_opd' => $pd->kode_opd,
            'nama' => $pd->nama_instansi,
            'singkatan' => $pd->singkatan,
            'total_program' => $totalProgram,
            'total_kegiatan' => $totalKegiatan,
            'total_sub_kegiatan' => $totalSubKegiatan,
            'pagu_anggaran' => $totalPagu,
            'realisasi_anggaran' => $totalRealisasiRp,
            'sisa_anggaran' => max(0, $totalPagu - $totalRealisasiRp),
            'capaian_fisik' => $rataCapaianKinerja,
            'serapan_keuangan' => $persenSerapanAnggaran,
            'nilai_komposit' => $nilaiKomposit,
            'predikat_fisik' => $predikatKinerja,
            'predikat_keuangan' => $predikatAnggaran,
            'predikat' => $predikatKomposit,
            'terisi_count' => $countTerisi,
            'submitted_count' => $countSubmitted,
            'verified_count' => $countVerified,
            'revisi_count' => $countRevisi,
            'final_count' => $countFinal,
            'progress_input' => $progressInput,
            'progress_verifikasi' => $progressVerifikasi,
            'status_laporan' => $statusLaporan,
        ];
    }

    /**
     * Ambil seluruh rekap ranking 37 OPD (dengan caching).
     */
    public static function getRekapDashboard(string $tahun = '2026', int $triwulan = 1, bool $forceRefresh = false): Collection
    {
        $cacheKey = "monev_rekap_dashboard_{$tahun}_tw{$triwulan}";

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        $rows = Cache::remember($cacheKey, now()->addMinutes(15), function () use ($tahun, $triwulan) {
            $pds = PerangkatDaerah::orderBy('id')->get();
            $allRenjas = Renja::where('tahun', $tahun)->get()->groupBy('perangkat_daerah_id');
            $allRealisasis = MonevRealisasi::where('tahun', $tahun)
                ->when($triwulan > 0, fn ($q) => $q->where('triwulan', $triwulan))
                ->get()
                ->groupBy('perangkat_daerah_id');

            $result = [];
            foreach ($pds as $pd) {
                $result[] = self::hitungRekapOpd(
                    $pd,
                    $tahun,
                    $triwulan,
                    $allRenjas->get($pd->id, collect()),
                    $allRealisasis->get($pd->id, collect())
                );
            }

            // Urutkan berdasarkan nilai_komposit tertinggi, lalu capaian_fisik, lalu serapan_keuangan
            usort($result, function ($a, $b) {
                if ($b['nilai_komposit'] !== $a['nilai_komposit']) {
                    return $b['nilai_komposit'] <=> $a['nilai_komposit'];
                }
                if ($b['capaian_fisik'] !== $a['capaian_fisik']) {
                    return $b['capaian_fisik'] <=> $a['capaian_fisik'];
                }

                return $b['serapan_keuangan'] <=> $a['serapan_keuangan'];
            });

            return $result;
        });

        return collect($rows);
    }

    /**
     * Hitung Statistik Agregat Kabupaten Badung.
     */
    public static function getStatistikKabupaten(string $tahun = '2026', int $triwulan = 1): array
    {
        $rekap = self::getRekapDashboard($tahun, $triwulan);

        $totalOpd = $rekap->count();
        $totalPaguKab = (float) $rekap->sum('pagu_anggaran');
        $totalRealisasiKab = (float) $rekap->sum('realisasi_anggaran');
        $totalSubKegiatanKab = (int) $rekap->sum('total_sub_kegiatan');
        $totalTerverifikasiKab = (int) $rekap->sum('verified_count');

        $rataFisikKab = $totalOpd > 0 ? round((float) $rekap->avg('capaian_fisik'), 2) : 0.0;
        $serapanKeuanganKab = self::hitungPersenAnggaran($totalRealisasiKab, $totalPaguKab);
        $rataKompositKab = round(($rataFisikKab * 0.5) + ($serapanKeuanganKab * 0.5), 2);

        $opdSudahInput = $rekap->where('terisi_count', '>', 0)->count();
        $opdSudahVerif = $rekap->where('progress_verifikasi', '>=', 100)->count();
        $opdSerapanRendah = $rekap->filter(fn ($r) => $r['serapan_keuangan'] <= 65.0)->count();

        // Data Tren Triwulan I - IV tingkat Kabupaten
        $trenTriwulan = [];
        for ($tw = 1; $tw <= 4; $tw++) {
            $realTw = MonevRealisasi::where('tahun', $tahun)->where('triwulan', $tw)->get();
            $fisikTw = $realTw->count() > 0 ? round((float) $realTw->avg('persen_capaian_kinerja'), 2) : 0.0;
            $uangTw = self::hitungPersenAnggaran((float) $realTw->sum('realisasi_anggaran'), $totalPaguKab);
            $trenTriwulan[$tw] = [
                'triwulan' => "TW {$tw}",
                'fisik' => $fisikTw,
                'keuangan' => $uangTw,
                'realisasi_rp' => (float) $realTw->sum('realisasi_anggaran'),
            ];
        }

        return [
            'tahun' => $tahun,
            'triwulan' => $triwulan,
            'total_opd' => $totalOpd,
            'opd_sudah_input' => $opdSudahInput,
            'opd_sudah_verif' => $opdSudahVerif,
            'opd_serapan_rendah' => $opdSerapanRendah,
            'total_rpjmd' => Rpjmd::count(),
            'total_rkpd' => Rkpd::where('tahun', $tahun)->count(),
            'total_renstra' => Renstra::count(),
            'total_sub_kegiatan' => $totalSubKegiatanKab,
            'total_terverifikasi' => $totalTerverifikasiKab,
            'total_pagu_rp' => $totalPaguKab,
            'total_realisasi_rp' => $totalRealisasiKab,
            'sisa_pagu_rp' => max(0, $totalPaguKab - $totalRealisasiKab),
            'rata_fisik' => $rataFisikKab,
            'serapan_keuangan' => $serapanKeuanganKab,
            'nilai_komposit' => $rataKompositKab,
            'predikat_fisik' => self::getPredikatPermendagri($rataFisikKab),
            'predikat_keuangan' => self::getPredikatPermendagri($serapanKeuanganKab),
            'predikat_kabupaten' => self::getPredikatPermendagri($rataKompositKab),
            'tren_triwulan' => $trenTriwulan,
        ];
    }
}
