<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RkpdResource\Pages;
use App\Models\Rkpd;
use App\Models\Rpjmd;
use App\Models\Satuan;
use App\Services\MonevService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class RkpdResource extends Resource
{
    protected static ?string $model = Rkpd::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = '2. RKPD (Tahunan Kab)';

    protected static ?string $modelLabel = 'RKPD Tahunan Kabupaten';

    protected static ?string $pluralModelLabel = 'Master RKPD (Tahunan Kabupaten)';

    protected static ?string $navigationGroup = 'Dokumen Perencanaan';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator', 'perangkat_daerah', 'viewer']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Rencana Kerja Pemerintah Daerah (RKPD) Tahunan Kabupaten')
                    ->description('Turunan tahunan dari RPJMD (Prioritas Daerah, Sasaran Pembangunan Tahun Berjalan & Pagu Indikatif)')
                    ->schema([
                        Forms\Components\Select::make('rpjmd_id')
                            ->label('Cascading Sasaran & IKU RPJMD (5 Tahunan)')
                            ->options(Rpjmd::orderBy('id')->get()->mapWithKeys(fn ($r) => [$r->id => "[{$r->kode_iku}] Sasaran: {$r->sasaran} — IKU: ".($r->nama_iku ?: $r->indikator_iku)]))
                            ->searchable()
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('tahun')
                            ->label('Tahun RKPD')
                            ->default('2026')
                            ->required()
                            ->maxLength(4),
                        Forms\Components\TextInput::make('kode_prioritas')
                            ->label('Kode Prioritas')
                            ->placeholder('PRIO-2026-01'),
                        Forms\Components\TextInput::make('prioritas_daerah')
                            ->label('Prioritas Pembangunan Daerah')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('sasaran_pembangunan')
                            ->label('Sasaran Pembangunan Tahun Berjalan')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('indikator_sasaran')
                            ->label('Indikator Sasaran RKPD')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('target_kinerja')
                            ->label('Target Kinerja Tahunan')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('satuan')
                            ->label('Satuan')
                            ->options(fn () => Satuan::getOptions())
                            ->searchable()
                            ->options(fn () => Satuan::getOptions())
                            ->searchable()
                            ->default('%')
                            ->required(),
                        Forms\Components\TextInput::make('pagu_indikatif')
                            ->label('Pagu Indikatif Kabupaten (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan')
                            ->columnSpan(2),
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
                Tables\Columns\TextColumn::make('kode_prioritas')
                    ->label('Kode')
                    ->badge()
                    ->color('warning')
                    ->searchable(),
                Tables\Columns\TextColumn::make('prioritas_daerah')
                    ->label('Prioritas Daerah & Sasaran RKPD')
                    ->description(fn (Rkpd $record): string => 'Sasaran: '.Str::limit($record->sasaran_pembangunan, 85))
                    ->weight('bold')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('indikator_sasaran')
                    ->label('Indikator Sasaran')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_kinerja')
                    ->label('Target')
                    ->formatStateUsing(fn (Rkpd $r) => number_format($r->target_kinerja, 2, ',', '.').' '.$r->satuan)
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('pagu_indikatif')
                    ->label('Pagu Indikatif Kabupaten')
                    ->formatStateUsing(fn ($state) => MonevService::formatRupiah((float) $state))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun')
                    ->options(['2025' => '2025', '2026' => '2026', '2027' => '2027']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRkpds::route('/'),
        ];
    }
}
