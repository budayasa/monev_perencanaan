<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    public function getHeading(): string|Htmlable
    {
        return 'E-Monev Perencanaan Kabupaten Badung';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Sistem Pengendalian & Evaluasi RPJMD, RKPD, Renstra & Renja (Permendagri 86/2017)';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('username')
                    ->label('Username SSO / NIP')
                    ->required()
                    ->autofocus()
                    ->extraInputAttributes(['tabindex' => 1]),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('Username atau password yang Anda masukkan salah.'),
        ]);
    }
}
