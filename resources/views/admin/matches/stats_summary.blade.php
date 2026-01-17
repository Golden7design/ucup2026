@extends('layouts.admin')

@section('title', 'Résumé Statistiques - ' . $match->homeTeam->university->name . ' vs ' . $match->awayTeam->university->name)

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-gray-800 shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-chart-pie mr-2"></i>
                Résumé des Statistiques
            </h1>
            <div class="space-x-4">
                <a href="{{ route('admin.matches.stats.edit', $match) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
                <a href="{{ route('admin.matches.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>

        <div class="bg-gray-700 rounded-lg p-4 mb-6">
            <h2 class="text-xl font-semibold text-white mb-4">Match Information</h2>
            <div class="flex justify-between items-center">
                <div class="text-center">
                    <div class="font-bold text-lg">{{ $match->homeTeam->university->name }}</div>
                    <div class="text-gray-300">{{ $match->homeTeam->name }}</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-400">
                        {{ $match->home_score }} - {{ $match->away_score }}
                    </div>
                    <div class="text-sm text-gray-400">
                        {{ $match->match_date->format('d/m/Y H:i') }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ ucfirst($match->status) }} | {{ ucfirst(str_replace('_', ' ', $match->match_type)) }}
                    </div>
                </div>
                <div class="text-center">
                    <div class="font-bold text-lg">{{ $match->awayTeam->university->name }}</div>
                    <div class="text-gray-300">{{ $match->awayTeam->name }}</div>
                </div>
            </div>
        </div>

        @if($match->admin_notes || $match->match_report)
        <div class="bg-gray-700 rounded-lg p-4 mb-6">
            <h2 class="text-xl font-semibold text-white mb-4">Notes et Rapport</h2>
            
            @if($match->admin_notes)
            <div class="mb-4">
                <h3 class="text-lg font-medium text-white mb-2">Notes de l'Admin</h3>
                <div class="bg-gray-600 rounded p-3 text-white">
                    {{ $match->admin_notes }}
                </div>
            </div>
            @endif
            
            @if($match->match_report)
            <div>
                <h3 class="text-lg font-medium text-white mb-2">Rapport de Match</h3>
                <div class="bg-gray-600 rounded p-3 text-white whitespace-pre-line">
                    {{ $match->match_report }}
                </div>
            </div>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Statistiques de possession -->
            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Possession</h3>
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <div class="text-sm text-gray-300 mb-1">{{ $match->homeTeam->university->short_name }}</div>
                        <div class="text-2xl font-bold text-white">{{ $match->home_possession ?? 50 }}%</div>
                    </div>
                    <div class="w-px bg-gray-500 h-16"></div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-300 mb-1">{{ $match->awayTeam->university->short_name }}</div>
                        <div class="text-2xl font-bold text-white">{{ $match->away_possession ?? 50 }}%</div>
                    </div>
                </div>
                <div class="mt-4 h-2 bg-gray-600 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full" style="width: {{ $match->home_possession ?? 50 }}%"></div>
                    <div class="bg-green-500 h-full" style="width: {{ $match->away_possession ?? 50 }}%"></div>
                </div>
            </div>

            <!-- Statistiques principales -->
            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Statistiques Principales</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-300">Tirs</span>
                        <span class="font-bold text-white">{{ $match->home_shots ?? 0 }} - {{ $match->away_shots ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Tirs cadrés</span>
                        <span class="font-bold text-white">{{ $match->home_shots_on_target ?? 0 }} - {{ $match->away_shots_on_target ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Corners</span>
                        <span class="font-bold text-white">{{ $match->home_corners ?? 0 }} - {{ $match->away_corners ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Fautes</span>
                        <span class="font-bold text-white">{{ $match->home_fouls ?? 0 }} - {{ $match->away_fouls ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Cartons -->
            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Cartons</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-300">Cartons Jaunes</span>
                        <span class="font-bold text-yellow-400">
                            {{ $match->home_yellow_cards ?? 0 }} - {{ $match->away_yellow_cards ?? 0 }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Cartons Rouges</span>
                        <span class="font-bold text-red-400">
                            {{ $match->home_red_cards ?? 0 }} - {{ $match->away_red_cards ?? 0 }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Hors-jeu</span>
                        <span class="font-bold text-white">
                            {{ $match->home_offsides ?? 0 }} - {{ $match->away_offsides ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Résumé -->
            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Résumé</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-300">Statut</span>
                        <span class="font-bold {{ $match->status === 'finished' ? 'text-green-400' : 'text-yellow-400' }}">
                            {{ ucfirst($match->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Type</span>
                        <span class="font-bold text-white">
                            {{ ucfirst(str_replace('_', ' ', $match->match_type)) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Date</span>
                        <span class="font-bold text-white">
                            {{ $match->match_date->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-300">Lieu</span>
                        <span class="font-bold text-white">
                            {{ $match->venue ?? 'Non spécifié' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if($match->status === 'finished')
        <div class="bg-gray-700 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4">Actions</h3>
            <div class="flex space-x-4">
                <a href="{{ route('admin.matches.stats.edit', $match) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                    <i class="fas fa-edit mr-2"></i> Modifier les Statistiques
                </a>
                <a href="{{ route('admin.live.show', $match) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition">
                    <i class="fas fa-broadcast-tower mr-2"></i> Voir la Gestion Live
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection