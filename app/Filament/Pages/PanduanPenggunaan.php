<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PanduanPenggunaan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Panduan Penggunaan';

    protected static ?string $title = 'Panduan Penggunaan E-Monev Perencanaan Berdasarkan Role (Permendagri 86/2017)';

    protected static ?int $navigationSort = 12;

    protected static string $view = 'filament.pages.panduan-penggunaan';
}
