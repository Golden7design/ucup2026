<?php

namespace App\Providers\Filament;

use App\Filament\Resources\UserResource;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin/filament')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->middleware([
                EncryptCookies::class,
                StartSession::class,
                //AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // Ajouter des widgets globaux ici si nécessaire
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->brandName('U-Cup Tournament Admin')
            ->favicon(asset('favicon.ico'))
            ->navigationGroups([
                'Gestion des utilisateurs',
                'Gestion du contenu',
                'Paramètres',
            ]);
    }
}