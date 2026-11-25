<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Services\KinerjaHarianService;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Page;

class JurnalKinerjaHarian extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Jurnal Kinerja Harian';

    protected static ?string $title = 'Jurnal & Bukti Kinerja Harian';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.pages.jurnal-kinerja-harian';

    public string $tanggal = '';

    public ?int $user_id = null;

    public function mount(): void
    {
        $this->tanggal = now()->toDateString();
        $this->user_id = auth()->id();
    }

    public function setHariIni(): void
    {
        $this->tanggal = now()->toDateString();
    }

    public function setKemarin(): void
    {
        $this->tanggal = now()->subDay()->toDateString();
    }

    public function pilihTanggal(string $tgl): void
    {
        $this->tanggal = $tgl;
    }

    public function getTargetUserId(): int
    {
        $currentUser = auth()->user();
        if ($currentUser->role === 'admin' && ! empty($this->user_id)) {
            return (int) $this->user_id;
        }

        return (int) $currentUser->id;
    }

    protected function getViewData(): array
    {
        $targetUserId = $this->getTargetUserId();
        $tanggalAktif = ! empty($this->tanggal) ? $this->tanggal : now()->toDateString();

        $rekap = KinerjaHarianService::getRekapHarian($targetUserId, $tanggalAktif);

        $daftarPegawai = auth()->user()->role === 'admin'
            ? User::with('perangkatDaerah')
                ->whereIn('role', ['admin', 'evaluator', 'perangkat_daerah'])
                ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'evaluator' THEN 2 WHEN 'perangkat_daerah' THEN 3 ELSE 4 END")
                ->orderBy('name')
                ->get()
            : collect();

        return array_merge($rekap, [
            'isAdmin' => auth()->user()->role === 'admin',
            'daftarPegawai' => $daftarPegawai,
        ]);
    }

    public function cetakBuktiPdf()
    {
        $targetUserId = $this->getTargetUserId();
        $tanggalAktif = ! empty($this->tanggal) ? $this->tanggal : now()->toDateString();
        $rekap = KinerjaHarianService::getRekapHarian($targetUserId, $tanggalAktif);

        $pdf = Pdf::loadView('pdf.bukti-kinerja-harian', $rekap)
            ->setPaper('a4', 'portrait');

        $namaBersih = preg_replace('/[^A-Za-z0-9\-]/', '-', $rekap['user']->name ?? 'Pegawai');
        $namaFile = "Bukti-Kinerja-Harian-{$namaBersih}-{$rekap['tanggal']}.pdf";

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $namaFile);
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'evaluator', 'perangkat_daerah']);
    }
}
