<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalMonevResource\Pages;
use App\Models\JadwalMonev;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JadwalMonevResource extends Resource
{
    protected static ?string $model = JadwalMonev::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Jadwal Monev Triwulan';

    protected static ?string $modelLabel = 'Jadwal Monev Triwulan';

    protected static ?string $pluralModelLabel = 'Jadwal Buka-Tutup Monev per Triwulan';

    protected static ?string $navigationGroup = 'Pengaturan & Master';

    protected static ?int $navigationSort = 8;

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
                Forms\Components\Section::make('Jadwal Buka-Tutup Pengisian Monev per Triwulan (TW I – IV)')
                    ->description('Sistem otomatis mengunci pengisian OPD di backend apabila melewati batas waktu selesai.')
                    ->schema([
                        Forms\Components\TextInput::make('tahun')
                            ->label('Tahun Anggaran')
                            ->default('2026')
                            ->required()
                            ->maxLength(4),
                        Forms\Components\Select::make('triwulan')
                            ->label('Triwulan')
                            ->options([
                                1 => 'Triwulan I (Januari - Maret)',
                                2 => 'Triwulan II (April - Juni)',
                                3 => 'Triwulan III (Juli - September)',
                                4 => 'Triwulan IV (Oktober - Desember)',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('nama_periode')
                            ->label('Nama Periode Evaluasi')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\DateTimePicker::make('waktu_mulai')
                            ->label('Waktu Mulai Dibuka')
                            ->required(),
                        Forms\Components\DateTimePicker::make('waktu_selesai')
                            ->label('Batas Akhir Pengisian (Deadline)')
                            ->required(),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktifkan Periode Ini Sekarang')
                            ->default(true),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan / Pengumuman Jadwal')
                            ->columnSpanFull(),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('triwulan')
                    ->label('Triwulan')
                    ->formatStateUsing(fn ($state) => "Triwulan {$state}")
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_periode')
                    ->label('Nama Periode Evaluasi')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('waktu_mulai')
                    ->label('Waktu Mulai')
                    ->dateTime('d M Y, H:i'),
                Tables\Columns\TextColumn::make('waktu_selesai')
                    ->label('Batas Akhir (Deadline)')
                    ->dateTime('d M Y, H:i'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Periode Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('status_kunci')
                    ->label('Status Kunci Backend')
                    ->state(fn (JadwalMonev $r) => ($r->is_active && now()->between($r->waktu_mulai, $r->waktu_selesai)) ? 'DIBUKA' : 'TERKUNCI')
                    ->badge()
                    ->color(fn (string $state) => $state === 'DIBUKA' ? 'success' : 'danger'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJadwalMonevs::route('/'),
        ];
    }
}
