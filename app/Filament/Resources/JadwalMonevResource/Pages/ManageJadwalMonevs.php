<?php

namespace App\Filament\Resources\JadwalMonevResource\Pages;

use App\Filament\Resources\JadwalMonevResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJadwalMonevs extends ManageRecords
{
    protected static string $resource = JadwalMonevResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Jadwal Triwulan'),
        ];
    }
}
