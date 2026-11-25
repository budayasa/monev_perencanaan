<?php

namespace App\Filament\Resources\RkpdResource\Pages;

use App\Filament\Resources\RkpdResource;
use App\Models\Rkpd;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRkpds extends ManageRecords
{
    protected static string $resource = RkpdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Prioritas RKPD'),
            Actions\Action::make('exportCsv')
                ->label('Unduh CSV RKPD')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    return response()->streamDownload(function () {
                        $out = fopen('php://output', 'w');
                        fputcsv($out, ['tahun', 'kode_prioritas', 'prioritas_daerah', 'sasaran_pembangunan', 'indikator_sasaran', 'target_kinerja', 'satuan', 'pagu_indikatif'], ';');
                        foreach (Rkpd::orderBy('id')->get() as $r) {
                            fputcsv($out, [
                                $r->tahun,
                                $r->kode_prioritas,
                                $r->prioritas_daerah,
                                $r->sasaran_pembangunan,
                                $r->indikator_sasaran,
                                $r->target_kinerja,
                                $r->satuan,
                                $r->pagu_indikatif,
                            ], ';');
                        }
                        fclose($out);
                    }, 'Data-RKPD-Badung.csv');
                }),
        ];
    }
}
