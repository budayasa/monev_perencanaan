<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SatuanResource\Pages;
use App\Models\Satuan;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SatuanResource extends Resource
{
    protected static ?string $model = Satuan::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationLabel = 'Master Satuan Indikator';

    protected static ?string $modelLabel = 'Satuan Indikator';

    protected static ?string $pluralModelLabel = 'Master Satuan Indikator Perencanaan';

    protected static ?string $navigationGroup = 'Pengaturan & Master';

    protected static ?int $navigationSort = 7;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator']);
    }

    public static function canCreate(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'evaluator']);
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
                Forms\Components\TextInput::make('nama_satuan')
                    ->label('Nama Satuan')
                    ->placeholder('Contoh: %, Indeks, Poin, Dokumen, Km')
                    ->required()
                    ->maxLength(80)
                    ->rule(static function (?Satuan $record): Closure {
                        return function (string $attribute, $value, Closure $fail) use ($record): void {
                            $normalized = mb_strtolower(trim((string) $value));
                            $exists = Satuan::query()
                                ->whereRaw('LOWER(TRIM(nama_satuan)) = ?', [$normalized])
                                ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                                ->exists();

                            if ($exists) {
                                $fail("Satuan \"{$value}\" sudah ada di Master Satuan. Tidak boleh menginput satuan yang sama.");
                            }
                        };
                    }),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif Digunakan di Form Input')
                    ->default(true),
                Forms\Components\Textarea::make('keterangan')
                    ->label('Keterangan / Penggunaan Satuan')
                    ->placeholder('Contoh: Digunakan untuk indikator persentase atau indeks...')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('No')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_satuan')
                    ->label('Nama Satuan')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->wrap()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
            ])
            ->defaultSort('nama_satuan')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSatuans::route('/'),
        ];
    }
}
