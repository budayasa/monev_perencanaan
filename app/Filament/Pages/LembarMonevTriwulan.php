<?php

namespace App\Filament\Pages;

use App\Models\JadwalMonev;
use App\Models\MonevRealisasi;
use App\Models\PerangkatDaerah;
use App\Models\Renja;
use App\Services\AiMonevService;
use App\Services\MonevService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class LembarMonevTriwulan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Monev Triwulan I–IV';

    protected static ?string $title = 'Pengisian & Verifikasi Monev Triwulan I–IV (Permendagri 86/2017)';

    protected static ?string $navigationGroup = 'Pengendalian & Evaluasi';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.lembar-monev-triwulan';

    public string $tahun = '2026';

    public int $triwulan = 1;

    public ?int $selectedPdId = null;

    // State form input & verifikasi per renja_id
    public array $inputs = [];

    public ?array $aiAnalisis = null;

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator', 'perangkat_daerah', 'viewer']);
    }

    public function mount(): void
    {
        $periode = MonevService::getPeriodeAktif();
        $this->tahun = $periode['tahun'];
        $this->triwulan = $periode['triwulan'];

        $user = auth()->user();
        if ($user->role === 'perangkat_daerah' && $user->perangkat_daerah_id) {
            $this->selectedPdId = (int) $user->perangkat_daerah_id;
        } else {
            $this->selectedPdId = (int) (request()->query('pd_id') ?: PerangkatDaerah::orderBy('id')->value('id'));
        }

        $this->loadMonevData();
    }

    public function pilihTriwulan(int $tw): void
    {
        $this->triwulan = $tw;
        session(['triwulan_aktif' => $tw]);
        $this->aiAnalisis = null;
        $this->loadMonevData();
    }

    public function updatedTahun(): void
    {
        session(['tahun_aktif' => $this->tahun]);
        $this->loadMonevData();
    }

    public function updatedSelectedPdId(): void
    {
        $this->aiAnalisis = null;
        $this->loadMonevData();
    }

    public function isJadwalBuka(): bool
    {
        return JadwalMonev::isBuka($this->tahun, $this->triwulan);
    }

    public function loadMonevData(): void
    {
        if (! $this->selectedPdId) {
            return;
        }

        $renjas = Renja::where('perangkat_daerah_id', $this->selectedPdId)
            ->where('tahun', $this->tahun)
            ->orderBy('kode_sub_kegiatan')
            ->get();

        $realisasis = MonevRealisasi::where('perangkat_daerah_id', $this->selectedPdId)
            ->where('tahun', $this->tahun)
            ->where('triwulan', $this->triwulan)
            ->get()
            ->keyBy('renja_id');

        $this->inputs = [];
        foreach ($renjas as $renja) {
            /** @var MonevRealisasi|null $rel */
            $rel = $realisasis->get($renja->id);
            $this->inputs[$renja->id] = [
                'realisasi_kinerja' => $rel?->realisasi_kinerja ?? 0,
                'realisasi_anggaran' => $rel?->realisasi_anggaran ?? 0,
                'faktor_pendorong' => $rel?->faktor_pendorong ?? '',
                'faktor_penghambat' => $rel?->faktor_penghambat ?? '',
                'tindak_lanjut' => $rel?->tindak_lanjut ?? '',
                'link_bukti_dukung' => $rel?->link_bukti_dukung ?? '',
                'status' => $rel?->status ?? 'draft',
                'catatan_verifikator' => $rel?->catatan_verifikator ?? '',
            ];
        }
    }

    /**
     * Simpan 1 baris Sub-Kegiatan oleh OPD atau Admin/Evaluator.
     */
    public function simpanSubKegiatan(int $renjaId, ?string $statusBaru = null): void
    {
        $user = auth()->user();

        if ($user->role === 'viewer') {
            Notification::make()->title('Akses Read-Only')->danger()->send();

            return;
        }

        // Validasi Kunci Jadwal Otomatis di Backend untuk OPD
        if ($user->role === 'perangkat_daerah' && ! $this->isJadwalBuka()) {
            Notification::make()
                ->title('Jadwal Triwulan '.$this->triwulan.' Terkunci!')
                ->body('Batas waktu pengisian Monev Triwulan '.$this->triwulan.' telah ditutup oleh Bappeda.')
                ->danger()
                ->send();

            return;
        }

        $renja = Renja::findOrFail($renjaId);
        $row = $this->inputs[$renjaId] ?? [];

        $currentStatus = $row['status'] ?? 'draft';
        if ($user->role === 'perangkat_daerah' && in_array($currentStatus, ['verified', 'final'])) {
            Notification::make()
                ->title('Data Sudah Terverifikasi Bappeda')
                ->body('Sub-Kegiatan yang telah diverifikasi/final tidak dapat diubah kecuali dikembalikan revisi.')
                ->warning()
                ->send();

            return;
        }

        $statusToSave = $statusBaru ?? $currentStatus;

        $payload = [
            'perangkat_daerah_id' => $renja->perangkat_daerah_id,
            'tahun' => $this->tahun,
            'realisasi_kinerja' => (float) ($row['realisasi_kinerja'] ?? 0),
            'realisasi_anggaran' => (float) ($row['realisasi_anggaran'] ?? 0),
            'faktor_pendorong' => $row['faktor_pendorong'] ?? null,
            'faktor_penghambat' => $row['faktor_penghambat'] ?? null,
            'tindak_lanjut' => $row['tindak_lanjut'] ?? null,
            'link_bukti_dukung' => $row['link_bukti_dukung'] ?? null,
            'status' => $statusToSave,
        ];

        if (in_array($user->role, ['admin', 'evaluator'])) {
            $payload['catatan_verifikator'] = $row['catatan_verifikator'] ?? null;
            if (in_array($statusToSave, ['verified', 'revisi', 'final'])) {
                $payload['verified_by'] = $user->id;
                $payload['verified_at'] = now();
            }
        }

        MonevRealisasi::updateOrCreate(
            ['renja_id' => $renja->id, 'triwulan' => $this->triwulan],
            $payload
        );

        $this->inputs[$renjaId]['status'] = $statusToSave;

        Notification::make()
            ->title('Data Sub-Kegiatan Tersimpan!')
            ->body("Realisasi TW {$this->triwulan} untuk [{$renja->kode_sub_kegiatan}] berhasil diperbarui (Status: ".strtoupper($statusToSave).').')
            ->success()
            ->send();
    }

    /**
     * Kirim Seluruh Laporan Triwulan OPD ke Bappeda (status -> submitted).
     */
    public function kirimSemuaKeBappeda(): void
    {
        $user = auth()->user();

        if ($user->role === 'perangkat_daerah' && ! $this->isJadwalBuka()) {
            Notification::make()
                ->title('Pengiriman Ditolak: Jadwal Triwulan Terkunci!')
                ->body('Periode pengisian Triwulan '.$this->triwulan.' sedang ditutup di server.')
                ->danger()
                ->send();

            return;
        }

        foreach (array_keys($this->inputs) as $renjaId) {
            $curr = $this->inputs[$renjaId]['status'] ?? 'draft';
            $nextStatus = in_array($curr, ['verified', 'final']) ? $curr : 'submitted';
            $this->simpanSubKegiatan((int) $renjaId, $nextStatus);
        }

        $pd = PerangkatDaerah::find($this->selectedPdId);
        activity('monev_kinerja')
            ->causedBy($user)
            ->event('submitted_monev')
            ->log("Mengirim laporan realisasi Monev Triwulan {$this->triwulan} Tahun {$this->tahun} pada {$pd?->nama_instansi} ke Bappeda");

        Notification::make()
            ->title('Laporan Triwulan '.$this->triwulan.' Terkirim ke Bappeda!')
            ->body('Seluruh Sub-Kegiatan telah dikirim untuk diverifikasi oleh Tim Evaluasi Bappeda.')
            ->success()
            ->send();
    }

    /**
     * Verifikasi Massal seluruh Sub-Kegiatan OPD oleh Evaluator/Admin Bappeda.
     */
    public function verifikasiSemuaBappeda(string $statusVerif = 'verified'): void
    {
        $user = auth()->user();
        if (! in_array($user->role, ['admin', 'evaluator'])) {
            return;
        }

        foreach (array_keys($this->inputs) as $renjaId) {
            $this->simpanSubKegiatan((int) $renjaId, $statusVerif);
        }

        $pd = PerangkatDaerah::find($this->selectedPdId);
        activity('monev_kinerja')
            ->causedBy($user)
            ->event('verified_monev')
            ->log("Memverifikasi ({$statusVerif}) seluruh laporan Monev Triwulan {$this->triwulan} Tahun {$this->tahun} pada {$pd?->nama_instansi}");

        Notification::make()
            ->title('Verifikasi Bappeda Berhasil!')
            ->body("Seluruh Sub-Kegiatan {$pd?->nama_instansi} pada Triwulan {$this->triwulan} telah ditetapkan menjadi ".strtoupper($statusVerif).'.')
            ->success()
            ->send();
    }

    /**
     * ✨ AI Analisis Faktor Penghambat & Rekomendasi Tindak Lanjut pada OPD aktif.
     */
    public function jalankanAiAnalisisHambatan(): void
    {
        $service = new AiMonevService;
        $this->aiAnalisis = $service->generateAnalisisFaktorPenghambat($this->selectedPdId, $this->tahun, $this->triwulan);

        Notification::make()
            ->title('✨ Analisis Faktor Penghambat Selesai!')
            ->body('AI telah memetakan kendala pelaksanaan beserta rekomendasi tindak lanjut.')
            ->success()
            ->send();
    }

    protected function getViewData(): array
    {
        $user = auth()->user();
        $pd = PerangkatDaerah::find($this->selectedPdId);
        $renjas = Renja::with(['renstra.rpjmd', 'rkpd'])
            ->where('perangkat_daerah_id', $this->selectedPdId)
            ->where('tahun', $this->tahun)
            ->orderBy('kode_sub_kegiatan')
            ->get();

        $rekapOpd = $pd
            ? MonevService::hitungRekapOpd($pd, $this->tahun, $this->triwulan)
            : [];

        $jadwal = JadwalMonev::where('tahun', $this->tahun)
            ->where('triwulan', $this->triwulan)
            ->first();

        return [
            'user' => $user,
            'isOpd' => $user->role === 'perangkat_daerah',
            'isVerifikator' => in_array($user->role, ['admin', 'evaluator']),
            'isViewer' => $user->role === 'viewer',
            'daftarOpd' => PerangkatDaerah::orderBy('id')->get(),
            'pdAktif' => $pd,
            'renjas' => $renjas,
            'rekapOpd' => $rekapOpd,
            'jadwal' => $jadwal,
            'isBuka' => $this->isJadwalBuka(),
        ];
    }
}
