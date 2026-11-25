<?php

namespace App\AvatarProviders;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Illuminate\Database\Eloquent\Model;

class CustomAvatarProvider implements AvatarProvider
{
    public function get(Model $user): string
    {
        // Ambil inisial nama (Misal: Ari Erlangga -> AE)
        $initials = collect(explode(' ', $user->name))
            ->map(fn ($segment) => mb_substr($segment, 0, 1))
            ->join('');

        // Kita buat gambar SVG sendiri agar BISA DIWARNAI lewat CSS
        // Background: Putih (#FFFFFF), Text: Oranye (#FF6600)
        return 'data:image/svg+xml;utf8,'.rawurlencode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                <rect width="100" height="100" fill="#FF6600"/>
                <text x="50%" y="50%" font-family="sans-serif" font-size="45" font-weight="900" 
                    fill="white" text-anchor="middle" dy=".3em">'.$initials.'</text>
            </svg>
        ');
    }
}
