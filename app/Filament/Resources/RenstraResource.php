<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RenstraResource\Pages;
use App\Models\PerangkatDaerah;
use App\Models\Renstra;
use App\Models\Rpjmd;
use App\Models\Satuan;
use App\Services\MonevService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RenstraResource extends Resource
{
    protected static ?string $model = Renstra::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = '3. Renstra PD (5 Tahunan OPD)';

    protected static ?string $modelLabel = 'Renstra Perangkat Daerah';

    protected static ?string $pluralModelLabel = 'Master Renstra (5 Tahunan OPD)';

    protected static ?string $navigationGroup = 'Dokumen Perencanaan';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['perangkatDaerah', 'rpjmd']);
        $user = auth()->user();

        if ($user && $user->role === 'perangkat_daerah' && $user->perangkat_daerah_id) {
            $query->where('perangkat_daerah_id', $user->perangkat_daerah_id);
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'perangkat_daerah']);
    }

    public static function canEdit($record): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator', 'perangkat_daerah']);
    }

    public static function canDelete($record): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'perangkat_daerah']);
    }

    public static function form(Form $form): Form
    {
        $user = auth()->user();
        $isOpd = $user?->role === 'perangkat_daerah';
        $isVerifikator = in_array($user?->role, ['admin', 'evaluator']);

        return $form
            ->schema([
                Forms\Components\Section::make('Rencana Strategis (Renstra) 5 Tahunan Perangkat Daerah')
                    ->description('Tujuan & Sasaran Perangkat Daerah yang selaras dengan RPJMD, turun ke Program PD & target 5 tahunan')
                    ->schema([
                        Forms\Components\Select::make('perangkat_daerah_id')
                            ->label('Perangkat Daerah (OPD)')
                            ->options(PerangkatDaerah::orderBy('nama_instansi')->pluck('nama_instansi', 'id'))
                            ->default($user?->perangkat_daerah_id)
                            ->disabled($isOpd)
                            ->dehydrated()
                            ->searchable()
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Select::make('rpjmd_id')
                            ->label('Cascading Sasaran / IKU RPJMD Kabupaten')
                            ->options(Rpjmd::orderBy('id')->get()->mapWithKeys(fn ($r) => [$r->id => "[{$r->kode_iku}] Sasaran: {$r->sasaran} — IKU: ".($r->nama_iku ?: $r->indikator_iku)]))
                            ->searchable()
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('tujuan_pd')
                            ->label('Tujuan Perangkat Daerah')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Textarea::make('sasaran_pd')
                            ->label('Sasaran Perangkat Daerah')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('kode_program')
                            ->label('Kode Program SIPD')
                            ->placeholder('5.01.01.01'),
                        Forms\Components\TextInput::make('nama_program')
                            ->label('Nama Program Perangkat Daerah')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\Select::make('satuan')
                            ->label('Satuan')
                            ->options(fn () => Satuan::getOptions())
                            ->searchable()
                            ->options(fn () => Satuan::getOptions())
                            ->searchable()
                            ->default('%')
                            ->required(),
                        Forms\Components\TextInput::make('indikator_program')
                            ->label('Indikator Kinerja Program')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(4),

                Forms\Components\Section::make('Target Kinerja Program 5 Tahunan & Pagu Indikatif')
                    ->schema([
                        Forms\Components\TextInput::make('kondisi_awal')->label('Baseline')->numeric()->default(85),
                        Forms\Components\TextInput::make('target_t1')->label('T1 (2025)')->numeric()->default(88),
                        Forms\Components\TextInput::make('target_t2')->label('T2 (2026)')->numeric()->default(91),
                        Forms\Components\TextInput::make('target_t3')->label('T3 (2027)')->numeric()->default(94),
                        Forms\Components\TextInput::make('target_t4')->label('T4 (2028)')->numeric()->default(97),
                        Forms\Components\TextInput::make('target_t5')->label('T5 (2029)')->numeric()->default(100),
                        Forms\Components\TextInput::make('pagu_5_tahun')
                            ->label('Total Pagu 5 Tahun (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->columnSpan(2),
                    ])->columns(4),

                Forms\Components\Section::make('Verifikasi Keselarasan Renstra ↔ RPJMD (Bappeda)')
                    ->schema([
                        Forms\Components\Select::make('status_keselarasan')
                            ->label('Status Keselarasan dengan RPJMD')
                            ->options([
                                'selaras' => '✅ Selaras dengan RPJMD',
                                'perlu_revisi' => '⚠️ Perlu Revisi / Penyesuaian',
                                'belum_dicek' => '⏳ Belum Dicek Bappeda',
                            ])
                            ->default('selaras')
                            ->disabled(! $isVerifikator)
                            ->dehydrated(),
                        Forms\Components\Textarea::make('catatan_bappeda')
                            ->label('Catatan Verifikator Bappeda')
                            ->disabled(! $isVerifikator)
                            ->dehydrated()
                            ->columnSpan(3),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('perangkatDaerah.singkatan')
                    ->label('OPD')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kode_program')
                    ->label('Kode Program')
                    ->fontFamily('mono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_program')
                    ->label('Program & Indikator Renstra PD')
                    ->description(fn (Renstra $r): string => 'Indikator: '.$r->indikator_program)
                    ->weight('bold')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rpjmd.kode_iku')
                    ->label('Cascading RPJMD')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('target_t2')
                    ->label('Target 2026')
                    ->formatStateUsing(fn (Renstra $r) => $r->target_t2.' '.$r->satuan),
                Tables\Columns\TextColumn::make('pagu_5_tahun')
                    ->label('Pagu 5 Tahun')
                    ->formatStateUsing(fn ($state) => MonevService::formatRupiah((float) $state, true))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_keselarasan')
                    ->label('Keselarasan RPJMD')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'selaras' => 'Selaras RPJMD',
                        'perlu_revisi' => 'Perlu Revisi',
                        default => 'Belum Dicek',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'selaras' => 'success',
                        'perlu_revisi' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('perangkat_daerah_id')
                    ->label('Filter Perangkat Daerah')
                    ->options(PerangkatDaerah::orderBy('nama_instansi')->pluck('nama_instansi', 'id'))
                    ->searchable(),
                Tables\Filters\SelectFilter::make('status_keselarasan')
                    ->options([
                        'selaras' => 'Selaras RPJMD',
                        'perlu_revisi' => 'Perlu Revisi',
                        'belum_dicek' => 'Belum Dicek',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRenstras::route('/'),
        ];
    }
}
