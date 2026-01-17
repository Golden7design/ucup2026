<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalUsers = User::count();
        $adminUsers = User::where('is_admin', true)->count();
        $regularUsers = User::where('is_admin', false)->count();
        
        return [
            Stat::make('Total utilisateurs', $totalUsers)
                ->description($totalUsers > 0 ? "{$totalUsers} utilisateurs enregistrés" : 'Aucun utilisateur')
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Administrateurs', $adminUsers)
                ->description($adminUsers > 0 ? "{$adminUsers} administrateurs" : 'Aucun administrateur')
                ->icon('heroicon-o-shield-check')
                ->color('success'),

            Stat::make('Utilisateurs réguliers', $regularUsers)
                ->description($regularUsers > 0 ? "{$regularUsers} utilisateurs" : 'Aucun utilisateur régulier')
                ->icon('heroicon-o-user')
                ->color('info'),
        ];
    }
}