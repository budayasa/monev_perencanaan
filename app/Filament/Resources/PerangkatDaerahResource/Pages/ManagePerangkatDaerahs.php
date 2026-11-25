<?php

namespace App\Filament\Resources\PerangkatDaerahResource\Pages;

use App\Filament\Resources\PerangkatDaerahResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePerangkatDaerahs extends ManageRecords
{
    protected static string $resource = PerangkatDaerahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Perangkat Daerah'),
        ];
    }
}
