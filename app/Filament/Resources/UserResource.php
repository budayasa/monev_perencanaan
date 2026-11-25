<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\PerangkatDaerah;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Manajemen User & Role';

    protected static ?string $modelLabel = 'User / Pengguna';

    protected static ?string $pluralModelLabel = 'Manajemen Akun Pengguna & Role SSO';

    protected static ?string $navigationGroup = 'Pengaturan & Master';

    protected static ?int $navigationSort = 10;

    public static function canViewAny(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\TextInput::make('username')
                    ->label('Username SSO')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('nip')
                    ->label('NIP Pegawai'),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email(),
                Forms\Components\Select::make('role')
                    ->label('Role Sistem')
                    ->options([
                        'admin' => 'Admin (Bappeda)',
                        'evaluator' => 'Evaluator / Verifikator Bappeda',
                        'perangkat_daerah' => 'Perangkat Daerah (37 OPD)',
                        'viewer' => 'Pimpinan / Viewer (Read-Only)',
                    ])
                    ->required(),
                Forms\Components\Select::make('perangkat_daerah_id')
                    ->label('Perangkat Daerah (OPD)')
                    ->options(PerangkatDaerah::orderBy('nama_instansi')->pluck('nama_instansi', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('nama_pejabat')
                    ->label('Jabatan / Nama Pejabat'),
                Forms\Components\TextInput::make('jenis_evaluator')
                    ->label('Bidang Verifikator (Khusus Evaluator)'),
                Forms\Components\TextInput::make('password')
                    ->label('Password Lokal')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->description(fn (User $u) => $u->nama_pejabat ?? '')
                    ->weight('bold')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Username / NIP')
                    ->description(fn (User $u) => $u->nip ? 'NIP: '.$u->nip : '')
                    ->fontFamily('mono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'admin' => 'Admin Bappeda',
                        'evaluator' => 'Verifikator Bappeda',
                        'perangkat_daerah' => 'Operator OPD',
                        'viewer' => 'Pimpinan / Viewer',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'admin' => 'success',
                        'evaluator' => 'warning',
                        'perangkat_daerah' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('perangkatDaerah.nama_instansi')
                    ->label('Perangkat Daerah')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin Bappeda',
                        'evaluator' => 'Verifikator Bappeda',
                        'perangkat_daerah' => 'Operator OPD',
                        'viewer' => 'Pimpinan / Viewer',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
