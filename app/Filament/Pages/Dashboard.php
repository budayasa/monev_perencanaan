<?php

namespace App\Filament\Pages;

use App\Services\AiMonevService;
use App\Services\MonevService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Dashboard Eksekutif & Ranking';

    protected static ?string $title = 'Dashboard Eksekutif & Peringkat Capaian Monev 37 OPD Kabupaten Badung';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.dashboard-utama';

    public string $tahunTerkini = '2026';

    public int $triwulanTerkini = 1;

    public string $filterPredikat = 'semua';

    public string $search = '';

    public array $rekapData = [];

    public ?array $aiSummary = null;

    public ?array $aiHambatan = null;

    public function mount(): void
    {
        $periode = MonevService::getPeriodeAktif();
        $this->tahunTerkini = $periode['tahun'];
        $this->triwulanTerkini = $periode['triwulan'];
        $this->muatDataDashboard();
    }

    public function pilihTriwulan(int $tw): void
    {
        $this->triwulanTerkini = $tw;
        session(['triwulan_aktif' => $tw]);
        $this->aiSummary = null;
        $this->aiHambatan = null;
        $this->muatDataDashboard();
    }

    public function updatedTahunTerkini(): void
    {
        session(['tahun_aktif' => $this->tahunTerkini]);
        $this->muatDataDashboard();
    }

    public function muatDataDashboard(bool $forceRefresh = false): void
    {
        $this->rekapData = MonevService::getRekapDashboard($this->tahunTerkini, $this->triwulanTerkini, $forceRefresh)->all();
    }

    public function sinkronkanNilai(): void
    {
        $this->muatDataDashboard(true);

        Notification::make()
            ->title('Sinkronisasi Ranking & Realisasi Berhasil!')
            ->body("Seluruh capaian fisik, serapan anggaran, dan peringkat 37 OPD Tahun {$this->tahunTerkini} TW {$this->triwulanTerkini} telah diperbarui.")
            ->success()
            ->send();
    }

    public function generateAiExecutiveSummary(): void
    {
        $stats = $this->getStatistikKabupaten();
        $counts = $this->getPredikatCounts();
        $topOpds = array_slice($this->rekapData, 0, 3);
        $lowOpds = array_slice(array_reverse($this->rekapData), 0, 4);

        $service = new AiMonevService;
        $this->aiSummary = $service->generateExecutiveSummaryKabupaten(
            $stats,
            $counts,
            $topOpds,
            $lowOpds,
            $this->tahunTerkini,
            $this->triwulanTerkini
        );

        activity('monev_kinerja')
            ->causedBy(auth()->user())
            ->event('generated_ai')
            ->log("Menyusun ✨ AI Executive Summary Capaian Monev RKPD & Renja Kabupaten Badung Tahun {$this->tahunTerkini} TW {$this->triwulanTerkini}");

        Notification::make()
            ->title('✨ AI Executive Summary Berhasil Disusun!')
            ->body('Ringkasan eksekutif tingkat Kabupaten Badung siap ditinjau pimpinan.')
            ->success()
            ->send();
    }

    public function generateAiAnalisisPenghambat(): void
    {
        $user = auth()->user();
        $pdId = $user?->role === 'perangkat_daerah' ? $user->perangkat_daerah_id : null;

        $service = new AiMonevService;
        $this->aiHambatan = $service->generateAnalisisFaktorPenghambat($pdId, $this->tahunTerkini, $this->triwulanTerkini);

        activity('monev_kinerja')
            ->causedBy($user)
            ->event('generated_ai_hambatan')
            ->log("Menjalankan ✨ AI Analisis Faktor Penghambat & Tindak Lanjut Monev TW {$this->triwulanTerkini} Tahun {$this->tahunTerkini}");

        Notification::make()
            ->title('✨ AI Analisis Faktor Penghambat Selesai!')
            ->body('Identifikasi sub-kegiatan tertinggal dan rekomendasi percepatan siap ditinjau.')
            ->success()
            ->send();
    }

    public function exportCsv()
    {
        return response()->streamDownload(function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Peringkat',
                'Kode OPD',
                'Nama Perangkat Daerah',
                'Jumlah Sub-Kegiatan',
                'Pagu Anggaran (Rp)',
                'Realisasi Anggaran (Rp)',
                '% Capaian Fisik',
                '% Serapan Keuangan',
                'Nilai Komposit (%)',
                'Predikat Permendagri 86/2017',
                'Status Verifikasi Bappeda',
            ], ';');

            foreach ($this->rekapData as $idx => $row) {
                fputcsv($file, [
                    $idx + 1,
                    $row['kode_opd'],
                    $row['nama'],
                    $row['total_sub_kegiatan'],
                    $row['pagu_anggaran'],
                    $row['realisasi_anggaran'],
                    number_format((float) $row['capaian_fisik'], 2, ',', '.'),
                    number_format((float) $row['serapan_keuangan'], 2, ',', '.'),
                    number_format((float) $row['nilai_komposit'], 2, ',', '.'),
                    $row['predikat']['label'] ?? '-',
                    $row['status_laporan'],
                ], ';');
            }
            fclose($file);
        }, "Ranking-Monev-Badung-{$this->tahunTerkini}-TW{$this->triwulanTerkini}.csv");
    }

    public function getStatistikKabupaten(): array
    {
        return MonevService::getStatistikKabupaten($this->tahunTerkini, $this->triwulanTerkini);
    }

    public function getPredikatCounts(): array
    {
        $counts = [
            'semua' => count($this->rekapData),
            'Sangat Tinggi' => 0,
            'Tinggi' => 0,
            'Sedang' => 0,
            'Rendah' => 0,
            'Sangat Rendah' => 0,
        ];

        foreach ($this->rekapData as $row) {
            $lbl = $row['predikat']['label'] ?? 'Sangat Rendah';
            if (isset($counts[$lbl])) {
                $counts[$lbl]++;
            }
        }

        return $counts;
    }

    public function getFilteredRekapData(): array
    {
        $items = [];
        foreach ($this->rekapData as $index => $row) {
            $row['rank'] = $index + 1;
            $labelPred = $row['predikat']['label'] ?? '';

            if ($this->filterPredikat !== 'semua' && $labelPred !== $this->filterPredikat) {
                continue;
            }

            if ($this->search !== '' && ! str_contains(strtolower($row['nama'].' '.($row['singkatan'] ?? '')), strtolower($this->search))) {
                continue;
            }

            $items[] = $row;
        }

        return $items;
    }
}
