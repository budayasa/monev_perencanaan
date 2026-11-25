<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RpjmdResource\Pages;
use App\Models\RpjmdVisi;
use Filament\Resources\Resource;

class RpjmdResource extends Resource
{
    protected static ?string $model = RpjmdVisi::class;

    protected static ?string $slug = 'rpjmds';

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $navigationLabel = '1. RPJMD (5 Tahunan Kab)';

    protected static ?string $modelLabel = 'RPJMD Kabupaten';

    protected static ?string $pluralModelLabel = 'Master RPJMD (5 Tahunan Kabupaten)';

    protected static ?string $navigationGroup = 'Dokumen Perencanaan';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator', 'perangkat_daerah', 'viewer']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRpjmds::route('/'),
        ];
    }
}
