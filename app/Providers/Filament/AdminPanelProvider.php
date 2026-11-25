<?php

namespace App\Providers\Filament;

use App\AvatarProviders\CustomAvatarProvider;
use App\Filament\Pages\Auth\CustomLogin;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\JurnalKinerjaHarian;
use App\Filament\Pages\LaporanHasilEvaluasi;
use App\Filament\Pages\LembarMonevTriwulan;
use App\Filament\Pages\LogAktivitasSistem;
use App\Filament\Pages\MasterNomenklatur;
use App\Filament\Pages\PanduanPenggunaan;
use App\Http\Middleware\SSOBrokerMiddleware;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CustomLogin::class)
            ->defaultAvatarProvider(CustomAvatarProvider::class)
            ->brandName('E-MONEV BADUNG')
            ->brandLogo(fn () => request()->is('admin/login*') ? asset('images/logo-login.png') : asset('images/logo.png'))
            ->brandLogoHeight(fn () => request()->is('admin/login*') ? '6rem' : '3rem')
            ->favicon(asset('images/logo.png'))
            ->font('Poppins')
            ->colors([
                'primary' => '#005000',
                'warning' => '#FF6600',
            ])
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('120s')
            ->spa()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
                LembarMonevTriwulan::class,
                LaporanHasilEvaluasi::class,
                JurnalKinerjaHarian::class,
                LogAktivitasSistem::class,
                MasterNomenklatur::class,
                PanduanPenggunaan::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                SSOBrokerMiddleware::class,
            ])
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): View => view('filament.hooks.custom-styles'),
            )
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn (): View => view('filament.hooks.mock-switcher'),
            )
            ->renderHook(
                PanelsRenderHook::RESOURCE_PAGES_LIST_RECORDS_TABLE_BEFORE,
                fn (): View => view('filament.hooks.resource-banner'),
            );
    }
}
