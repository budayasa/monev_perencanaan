<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerangkatDaerahResource\Pages;
use App\Models\PerangkatDaerah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PerangkatDaerahResource extends Resource
{
    protected static ?string $model = PerangkatDaerah::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Perangkat Daerah (37 OPD)';

    protected static ?string $modelLabel = 'Perangkat Daerah';

    protected static ?string $pluralModelLabel = 'Master 37 Perangkat Daerah Kabupaten Badung';

    protected static ?string $navigationGroup = 'Pengaturan & Master';

    protected static ?int $navigationSort = 9;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_opd')
                    ->label('Kode Urusan / OPD SIPD')
                    ->placeholder('5.01.01'),
                Forms\Components\TextInput::make('singkatan')
                    ->label('Singkatan OPD')
                    ->placeholder('BAPPEDA')
                    ->required(),
                Forms\Components\TextInput::make('nama_instansi')
                    ->label('Nama Lengkap Perangkat Daerah')
                    ->required()
                    ->columnSpan(2),
                Forms\Components\TextInput::make('kepala_opd')
                    ->label('Nama / Jabatan Kepala OPD'),
                Forms\Components\TextInput::make('nip_kepala')
                    ->label('NIP Kepala OPD'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode_opd')
                    ->label('Kode SIPD')
                    ->badge()
                    ->color('primary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('singkatan')
                    ->label('Singkatan')
                    ->badge()
                    ->color('warning')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_instansi')
                    ->label('Nama Perangkat Daerah')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('renjas_count')
                    ->counts('renjas')
                    ->label('Sub-Kegiatan')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Akun User')
                    ->badge()
                    ->color('info'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePerangkatDaerahs::route('/'),
        ];
    }
}
