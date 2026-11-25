<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RenjaResource\Pages;
use App\Models\PerangkatDaerah;
use App\Models\Renja;
use App\Models\Renstra;
use App\Models\Rkpd;
use App\Models\Satuan;
use App\Services\MonevService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RenjaResource extends Resource
{
    protected static ?string $model = Renja::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = '4. Renja PD (Sub-Kegiatan)';

    protected static ?string $modelLabel = 'Renja & Sub-Kegiatan OPD';

    protected static ?string $pluralModelLabel = 'Master Renja (Program → Kegiatan → Sub-Kegiatan)';

    protected static ?string $navigationGroup = 'Dokumen Perencanaan';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['perangkatDaerah', 'renstra', 'rkpd']);
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
                Forms\Components\Section::make('Hirarki Rencana Kerja (Renja) Tahunan Perangkat Daerah — SIPD')
                    ->description('Turunan tahunan Renstra hingga level Program → Kegiatan → Sub-Kegiatan')
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
                        Forms\Components\TextInput::make('tahun')
                            ->label('Tahun Anggaran')
                            ->default('2026')
                            ->required(),
                        Forms\Components\Select::make('rkpd_id')
                            ->label('Prioritas RKPD Kabupaten')
                            ->options(Rkpd::orderBy('id')->get()->mapWithKeys(fn ($r) => [$r->id => "[{$r->kode_prioritas}] {$r->prioritas_daerah}"]))
                            ->searchable(),
                        Forms\Components\Select::make('renstra_id')
                            ->label('Cascading Program Renstra PD')
                            ->options(Renstra::orderBy('id')->pluck('nama_program', 'id'))
                            ->searchable()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('kode_program')
                            ->label('Kode Program SIPD'),
                        Forms\Components\TextInput::make('nama_program')
                            ->label('Nama Program')
                            ->required(),
                        Forms\Components\TextInput::make('kode_kegiatan')
                            ->label('Kode Kegiatan SIPD'),
                        Forms\Components\TextInput::make('nama_kegiatan')
                            ->label('Nama Kegiatan')
                            ->required()
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('kode_sub_kegiatan')
                            ->label('Kode Sub-Kegiatan SIPD')
                            ->required(),
                        Forms\Components\TextInput::make('nama_sub_kegiatan')
                            ->label('Nama Sub-Kegiatan')
                            ->required()
                            ->columnSpan(3),
                        Forms\Components\TextInput::make('indikator_sub_kegiatan')
                            ->label('Indikator Kinerja Sub-Kegiatan')
                            ->required()
                            ->columnSpan(3),
                        Forms\Components\Select::make('satuan')
                            ->label('Satuan')
                            ->options(fn () => Satuan::getOptions())
                            ->searchable()
                            ->default('Dokumen')
                            ->required(),
                    ])->columns(4),

                Forms\Components\Section::make('Target Kinerja (Fisik) & Pagu Anggaran (Keuangan)')
                    ->schema([
                        Forms\Components\TextInput::make('target_kinerja_tahunan')
                            ->label('Target Kinerja Tahunan (Fisik)')
                            ->numeric()
                            ->default(100)
                            ->required(),
                        Forms\Components\TextInput::make('pagu_anggaran')
                            ->label('Pagu Anggaran Sub-Kegiatan (Rp)')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('sumber_dana')
                            ->label('Sumber Dana')
                            ->default('APBD Kabupaten Badung'),
                        Forms\Components\TextInput::make('target_tw1_fisik')->label('Target TW I')->numeric()->default(25),
                        Forms\Components\TextInput::make('target_tw2_fisik')->label('Target TW II')->numeric()->default(50),
                        Forms\Components\TextInput::make('target_tw3_fisik')->label('Target TW III')->numeric()->default(75),
                        Forms\Components\TextInput::make('target_tw4_fisik')->label('Target TW IV')->numeric()->default(100),
                    ])->columns(4),

                Forms\Components\Section::make('Verifikasi Keselarasan Renja ↔ RKPD (Bappeda)')
                    ->schema([
                        Forms\Components\Select::make('status_keselarasan')
                            ->label('Status Keselarasan dengan RKPD')
                            ->options([
                                'selaras' => '✅ Selaras dengan RKPD',
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
                Tables\Columns\TextColumn::make('kode_sub_kegiatan')
                    ->label('Kode Rekening SIPD')
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_sub_kegiatan')
                    ->label('Program → Kegiatan → Sub-Kegiatan')
                    ->description(fn (Renja $r): string => "Kegiatan: {$r->nama_kegiatan} | Indikator: {$r->indikator_sub_kegiatan}")
                    ->weight('bold')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('target_kinerja_tahunan')
                    ->label('Target Fisik')
                    ->formatStateUsing(fn (Renja $r) => number_format($r->target_kinerja_tahunan, 0, ',', '.').' '.$r->satuan)
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('pagu_anggaran')
                    ->label('Pagu Anggaran (Rp)')
                    ->formatStateUsing(fn ($state) => MonevService::formatRupiah((float) $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_keselarasan')
                    ->label('Keselarasan RKPD')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => $state === 'selaras' ? 'Selaras RKPD' : 'Perlu Revisi')
                    ->color(fn (string $state) => $state === 'selaras' ? 'success' : 'danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('perangkat_daerah_id')
                    ->label('Perangkat Daerah')
                    ->options(PerangkatDaerah::orderBy('nama_instansi')->pluck('nama_instansi', 'id'))
                    ->searchable(),
                Tables\Filters\SelectFilter::make('tahun')
                    ->options(['2025' => '2025', '2026' => '2026']),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRenjas::route('/'),
        ];
    }
}
