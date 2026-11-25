<?php

namespace App\Filament\Resources\RenjaResource\Pages;

use App\Filament\Resources\RenjaResource;
use App\Models\Renja;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Storage;

class ManageRenjas extends ManageRecords
{
    protected static string $resource = RenjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Sub-Kegiatan Renja'),
            Actions\Action::make('importCsv')
                ->label('Import CSV SIPD / Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->visible(fn () => in_array(auth()->user()?->role, ['admin', 'perangkat_daerah']))
                ->form([
                    Forms\Components\FileUpload::make('csv_file')
                        ->label('Upload File CSV Renja (perangkat_daerah_id;tahun;kode_program;nama_program;kode_kegiatan;nama_kegiatan;kode_sub_kegiatan;nama_sub_kegiatan;indikator;satuan;target;pagu)')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv'])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $path = Storage::disk('local')->path($data['csv_file']);
                    if (! file_exists($path)) {
                        $path = storage_path('app/public/'.$data['csv_file']);
                    }
                    if (! file_exists($path)) {
                        Notification::make()->title('File CSV tidak ditemukan')->danger()->send();

                        return;
                    }

                    $handle = fopen($path, 'r');
                    fgetcsv($handle, 2000, ';');
                    $count = 0;
                    $userPdId = auth()->user()?->perangkat_daerah_id;

                    while (($row = fgetcsv($handle, 2000, ';')) !== false) {
                        if (empty($row[6])) {
                            continue;
                        }
                        $pdId = $userPdId ?: (int) ($row[0] ?: 1);
                        Renja::updateOrCreate(
                            [
                                'perangkat_daerah_id' => $pdId,
                                'tahun' => trim($row[1] ?? '2026'),
                                'kode_sub_kegiatan' => trim($row[6]),
                            ],
                            [
                                'kode_program' => $row[2] ?? '',
                                'nama_program' => $row[3] ?? '-',
                                'kode_kegiatan' => $row[4] ?? '',
                                'nama_kegiatan' => $row[5] ?? '-',
                                'nama_sub_kegiatan' => $row[7] ?? '-',
                                'indikator_sub_kegiatan' => $row[8] ?? '-',
                                'satuan' => $row[9] ?? 'Dokumen',
                                'target_kinerja_tahunan' => (float) ($row[10] ?? 100),
                                'pagu_anggaran' => (float) ($row[11] ?? 0),
                            ]
                        );
                        $count++;
                    }
                    fclose($handle);

                    Notification::make()
                        ->title("Berhasil mengimpor {$count} Sub-Kegiatan Renja")
                        ->success()
                        ->send();
                }),
            Actions\Action::make('exportCsv')
                ->label('Unduh CSV Renja')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $out = fopen('php://output', 'w');
                        fputcsv($out, ['perangkat_daerah_id', 'tahun', 'kode_program', 'nama_program', 'kode_kegiatan', 'nama_kegiatan', 'kode_sub_kegiatan', 'nama_sub_kegiatan', 'indikator', 'satuan', 'target', 'pagu'], ';');
                        $query = Renja::orderBy('perangkat_daerah_id')->orderBy('kode_sub_kegiatan');
                        if (auth()->user()?->role === 'perangkat_daerah') {
                            $query->where('perangkat_daerah_id', auth()->user()->perangkat_daerah_id);
                        }
                        foreach ($query->get() as $r) {
                            fputcsv($out, [
                                $r->perangkat_daerah_id,
                                $r->tahun,
                                $r->kode_program,
                                $r->nama_program,
                                $r->kode_kegiatan,
                                $r->nama_kegiatan,
                                $r->kode_sub_kegiatan,
                                $r->nama_sub_kegiatan,
                                $r->indikator_sub_kegiatan,
                                $r->satuan,
                                $r->target_kinerja_tahunan,
                                $r->pagu_anggaran,
                            ], ';');
                        }
                        fclose($out);
                    }, 'Data-Renja-SIPD-Badung.csv');
                }),
        ];
    }
}
