<?php

namespace App\Filament\Resources\RenstraResource\Pages;

use App\Filament\Resources\RenstraResource;
use App\Models\Renstra;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRenstras extends ManageRecords
{
    protected static string $resource = RenstraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Program Renstra'),
            Actions\Action::make('exportCsv')
                ->label('Unduh CSV Renstra')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $out = fopen('php://output', 'w');
                        fputcsv($out, ['opd', 'kode_program', 'nama_program', 'indikator_program', 'satuan', 't1', 't2', 't3', 't4', 't5', 'pagu_5_tahun', 'status_keselarasan'], ';');
                        $query = Renstra::with('perangkatDaerah')->orderBy('perangkat_daerah_id');
                        if (auth()->user()?->role === 'perangkat_daerah') {
                            $query->where('perangkat_daerah_id', auth()->user()->perangkat_daerah_id);
                        }
                        foreach ($query->get() as $r) {
                            fputcsv($out, [
                                $r->perangkatDaerah?->nama_instansi,
                                $r->kode_program,
                                $r->nama_program,
                                $r->indikator_program,
                                $r->satuan,
                                $r->target_t1,
                                $r->target_t2,
                                $r->target_t3,
                                $r->target_t4,
                                $r->target_t5,
                                $r->pagu_5_tahun,
                                $r->status_keselarasan,
                            ], ';');
                        }
                        fclose($out);
                    }, 'Data-Renstra-OPD-Badung.csv');
                }),
        ];
    }
}
