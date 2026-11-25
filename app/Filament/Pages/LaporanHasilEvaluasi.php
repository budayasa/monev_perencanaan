<?php

namespace App\Filament\Pages;

use App\Models\BeritaAcaraMonev;
use App\Models\MonevRealisasi;
use App\Models\PerangkatDaerah;
use App\Models\Renja;
use App\Services\MonevService;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class LaporanHasilEvaluasi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationLabel = 'Cetakan & Berita Acara (A4)';

    protected static ?string $title = 'Editor Kertas A4 & Cetakan Resmi Evaluasi RKPD/Renja (Permendagri 86/2017)';

    protected static ?string $navigationGroup = 'Pengendalian & Evaluasi';

    protected static ?int $navigationSort = 6;

    protected static string $view = 'filament.pages.laporan-hasil-evaluasi';

    public string $tahun = '2026';

    public int $triwulan = 1;

    public ?int $selectedPdId = null;

    public string $nomorBa = '';

    public string $tanggalBa = '';

    public string $catatanEvaluasi = '';

    public string $rekomendasiBappeda = '';

    public string $statusBa = 'draft';

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

        $this->loadBeritaAcara();
    }

    public function updatedSelectedPdId(): void
    {
        $this->loadBeritaAcara();
    }

    public function updatedTahun(): void
    {
        $this->loadBeritaAcara();
    }

    public function pilihTriwulan(int $tw): void
    {
        $this->triwulan = $tw;
        $this->loadBeritaAcara();
    }

    public function loadBeritaAcara(): void
    {
        if (! $this->selectedPdId) {
            return;
        }

        $pd = PerangkatDaerah::find($this->selectedPdId);
        $ba = BeritaAcaraMonev::where('perangkat_daerah_id', $this->selectedPdId)
            ->where('tahun', $this->tahun)
            ->where('triwulan', $this->triwulan)
            ->first();

        $this->nomorBa = $ba?->nomor_ba ?? sprintf('050/%03d/BA-MONEV/BAPPEDA/%s', $this->selectedPdId, $this->tahun);
        $this->tanggalBa = $ba?->tanggal_ba?->toDateString() ?? now()->toDateString();
        $this->catatanEvaluasi = $ba?->catatan_evaluasi ?? (
            'Berdasarkan hasil evaluasi terhadap pelaksanaan Rencana Kerja (Renja) '.($pd?->nama_instansi ?? '').
            ' pada Triwulan '.$this->triwulan.' Tahun Anggaran '.$this->tahun.
            ' sesuai amanat Permendagri Nomor 86 Tahun 2017, pencapaian indikator kinerja fisik dan penyerapan anggaran perlu terus dikendalikan agar target akhir tahun tercapai 100%.'
        );
        $this->rekomendasiBappeda = $ba?->rekomendasi_bappeda ?? (
            "1. Melaksanakan percepatan pelaksanaan Sub-Kegiatan sesuai jadwal anggaran kas Triwulan berikutnya.\n".
            "2. Menyelesaikan faktor penghambat teknis dan administrasi pertanggungjawaban keuangan tepat waktu.\n".
            '3. Memastikan keselarasan penuh capaian Renja Perangkat Daerah terhadap sasaran prioritas RKPD Kabupaten Badung.'
        );
        $this->statusBa = $ba?->status ?? 'draft';
    }

    public function simpanBeritaAcara(string $status = 'terbit'): void
    {
        $user = auth()->user();
        if (! in_array($user->role, ['admin', 'evaluator'])) {
            Notification::make()->title('Hanya Admin/Verifikator Bappeda yang dapat menerbitkan Berita Acara')->danger()->send();

            return;
        }

        $this->statusBa = $status;

        BeritaAcaraMonev::updateOrCreate(
            [
                'perangkat_daerah_id' => $this->selectedPdId,
                'tahun' => $this->tahun,
                'triwulan' => $this->triwulan,
            ],
            [
                'nomor_ba' => $this->nomorBa,
                'tanggal_ba' => $this->tanggalBa ?: now()->toDateString(),
                'catatan_evaluasi' => $this->catatanEvaluasi,
                'rekomendasi_bappeda' => $this->rekomendasiBappeda,
                'status' => $status,
                'evaluator_id' => $user->id,
            ]
        );

        $pd = PerangkatDaerah::find($this->selectedPdId);
        activity('monev_kinerja')
            ->causedBy($user)
            ->event('published_ba')
            ->log("Menerbitkan Berita Acara & Laporan Hasil Evaluasi Renja TW {$this->triwulan} TA {$this->tahun} untuk {$pd?->nama_instansi}");

        Notification::make()
            ->title('Berita Acara & Laporan Evaluasi Tersimpan!')
            ->body('Naskah evaluasi resmi Permendagri 86/2017 siap dicetak ke PDF dan Word (.docx).')
            ->success()
            ->send();
    }

    public function unduhWordDocx()
    {
        $pd = PerangkatDaerah::findOrFail($this->selectedPdId);
        $rekap = MonevService::hitungRekapOpd($pd, $this->tahun, $this->triwulan);
        $renjas = Renja::where('perangkat_daerah_id', $pd->id)->where('tahun', $this->tahun)->orderBy('kode_sub_kegiatan')->get();
        $realisasis = MonevRealisasi::where('perangkat_daerah_id', $pd->id)
            ->where('tahun', $this->tahun)
            ->where('triwulan', $this->triwulan)
            ->get()
            ->keyBy('renja_id');

        $phpWord = new PhpWord;
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(11);

        $section = $phpWord->addSection([
            'orientation' => 'landscape',
            'marginTop' => 900,
            'marginBottom' => 900,
            'marginLeft' => 1000,
            'marginRight' => 1000,
        ]);

        $section->addText('PEMERINTAH KABUPATEN BADUNG', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $section->addText('BADAN PERENCANAAN PEMBANGUNAN DAERAH (BAPPEDA)', ['bold' => true, 'size' => 15, 'color' => '005000'], ['alignment' => 'center']);
        $section->addText('LAPORAN EVALUASI TERHADAP HASIL RENJA PERANGKAT DAERAH & RKPD (PERMENDAGRI 86/2017)', ['bold' => true, 'size' => 12], ['alignment' => 'center']);
        $section->addText("Perangkat Daerah: {$pd->nama_instansi} | Tahun Anggaran: {$this->tahun} | Triwulan: {$this->triwulan} | Nomor BA: {$this->nomorBa}", ['size' => 10], ['alignment' => 'center']);
        $section->addTextBreak(1);

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '333333',
            'cellMargin' => 60,
        ]);

        // Header Tabel Format Lampiran Permendagri 86/2017
        $table->addRow();
        $headers = ['No', 'Kode', 'Program / Kegiatan / Sub-Kegiatan', 'Indikator Kinerja', 'Target Tahunan', 'Pagu Anggaran (Rp)', 'Realisasi Fisik', '% Fisik', 'Realisasi Keuangan (Rp)', '% Keuangan', 'Faktor Penghambat & Tindak Lanjut'];
        foreach ($headers as $h) {
            $table->addCell(1400, ['bgColor' => '005000'])->addText($h, ['bold' => true, 'color' => 'FFFFFF', 'size' => 9]);
        }

        foreach ($renjas as $idx => $r) {
            $rel = $realisasis->get($r->id);
            $table->addRow();
            $table->addCell(500)->addText((string) ($idx + 1), ['size' => 9]);
            $table->addCell(1300)->addText((string) $r->kode_sub_kegiatan, ['size' => 9]);
            $table->addCell(2500)->addText($r->nama_sub_kegiatan, ['bold' => true, 'size' => 9]);
            $table->addCell(2200)->addText($r->indikator_sub_kegiatan, ['size' => 9]);
            $table->addCell(1100)->addText($r->target_kinerja_tahunan.' '.$r->satuan, ['size' => 9]);
            $table->addCell(1600)->addText(number_format($r->pagu_anggaran, 0, ',', '.'), ['size' => 9]);
            $table->addCell(1100)->addText(($rel?->realisasi_kinerja ?? 0).' '.$r->satuan, ['size' => 9]);
            $table->addCell(900)->addText(($rel?->persen_capaian_kinerja ?? 0).'%', ['bold' => true, 'size' => 9]);
            $table->addCell(1600)->addText(number_format($rel?->realisasi_anggaran ?? 0, 0, ',', '.'), ['size' => 9]);
            $table->addCell(900)->addText(($rel?->persen_serapan_anggaran ?? 0).'%', ['bold' => true, 'size' => 9]);
            $table->addCell(2400)->addText('Hambatan: '.($rel?->faktor_penghambat ?? '-').' | TL: '.($rel?->tindak_lanjut ?? '-'), ['size' => 8]);
        }

        $section->addTextBreak(1);
        $section->addText(
            "Rata-rata Capaian Kinerja Fisik: {$rekap['capaian_fisik']}% ({$rekap['predikat_fisik']['label']}) | ".
            "Realisasi Serapan Anggaran: {$rekap['serapan_keuangan']}% ({$rekap['predikat_keuangan']['label']}) | ".
            "Predikat Akhir Permendagri 86/2017: {$rekap['predikat']['label']}",
            ['bold' => true, 'size' => 11]
        );
        $section->addTextBreak(1);
        $section->addText('Catatan Evaluasi Bappeda:', ['bold' => true]);
        $section->addText($this->catatanEvaluasi);
        $section->addText('Rekomendasi Tindak Lanjut Bappeda:', ['bold' => true]);
        $section->addText($this->rekomendasiBappeda);

        $fileName = 'Laporan-Evaluasi-Permendagri86-'.($pd->singkatan ?: 'OPD')."-{$this->tahun}-TW{$this->triwulan}.docx";

        return response()->streamDownload(function () use ($phpWord) {
            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
        }, $fileName);
    }

    protected function getViewData(): array
    {
        $pd = PerangkatDaerah::find($this->selectedPdId);
        $renjas = Renja::where('perangkat_daerah_id', $this->selectedPdId)
            ->where('tahun', $this->tahun)
            ->orderBy('kode_sub_kegiatan')
            ->get();

        $realisasis = MonevRealisasi::where('perangkat_daerah_id', $this->selectedPdId)
            ->where('tahun', $this->tahun)
            ->where('triwulan', $this->triwulan)
            ->get()
            ->keyBy('renja_id');

        $rekap = $pd ? MonevService::hitungRekapOpd($pd, $this->tahun, $this->triwulan) : [];

        return [
            'user' => auth()->user(),
            'isOpd' => auth()->user()?->role === 'perangkat_daerah',
            'isVerifikator' => in_array(auth()->user()?->role, ['admin', 'evaluator']),
            'daftarOpd' => PerangkatDaerah::orderBy('id')->get(),
            'pdAktif' => $pd,
            'renjas' => $renjas,
            'realisasis' => $realisasis,
            'rekap' => $rekap,
        ];
    }
}
