@extends('layouts.admin')

@section('title', 'Gestion du Match en Direct')

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8 text-gray-100"
     x-data="{
         home_fouls: {{ $match->home_fouls ?? 0 }},
         home_corners: {{ $match->home_corners ?? 0 }},
         home_offsides: {{ $match->home_offsides ?? 0 }},
         home_shots: {{ $match->home_shots ?? 0 }},
         home_shots_on_target: {{ $match->home_shots_on_target ?? 0 }},
         home_saves: {{ $match->home_saves ?? 0 }},
         home_free_kicks: {{ $match->home_free_kicks ?? 0 }},
         home_throw_ins: {{ $match->home_throw_ins ?? 0 }},
         away_fouls: {{ $match->away_fouls ?? 0 }},
         away_corners: {{ $match->away_corners ?? 0 }},
         away_offsides: {{ $match->away_offsides ?? 0 }},
         away_shots: {{ $match->away_shots ?? 0 }},
         away_shots_on_target: {{ $match->away_shots_on_target ?? 0 }},
         away_saves: {{ $match->away_saves ?? 0 }},
         away_free_kicks: {{ $match->away_free_kicks ?? 0 }},
         away_throw_ins: {{ $match->away_throw_ins ?? 0 }},
         updateStat: function(statName, teamSide, action) {
             // Appel de la fonction globale
             window.updateStat.call(this, statName, teamSide, action);
         }
     }">

    <!-- Styles CSS pour l'interface admin -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Animation pour les boutons */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .pulse-button {
            animation: pulse 2s infinite;
        }
        
        /* Animation pour les mises à jour */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Animation pour les notifications */
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        .slide-in-right {
            animation: slideInRight 0.5s ease-out;
        }
        
        /* Style pour les cartes */
        .stat-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover::before {
            transform: scaleX(1);
        }
        
        /* Style pour les boutons d'action */
        .action-button {
            transition: all 0.2s ease;
            transform: translateY(0);
        }
        
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        
        .action-button:active {
            transform: translateY(0);
        }
        
        /* Style pour les événements */
        .event-item {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .event-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Style pour le score */
        .score-display {
            font-family: 'Roboto Condensed', sans-serif;
            font-weight: 900;
            background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .grid-cols-3 {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .lg\:grid-cols-3 {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <!-- En-tête de la page -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-100 flex items-center">
            <i class="fas fa-bullhorn text-yellow-500 mr-2"></i> Gestion en Direct
        </h1>
        <a href="{{ route('admin.matches.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out flex items-center action-button">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    <!-- Messages de succès/erreur -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <hr class="mb-6 border-gray-700">

    <!-- Notification de synchronisation -->
    <div id="sync-notification" class="fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 opacity-0 pointer-events-none slide-in-right" style="z-index: 1000;">
        <i class="fas fa-sync-alt animate-spin"></i>
        <span>Synchronisation en temps réel active</span>
        <button onclick="this.parentElement.style.opacity = '0'; this.parentElement.style.pointerEvents = 'none'" class="text-green-200 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Section du match en direct -->
    <div class="bg-gray-800 shadow-lg rounded-lg p-6 mb-8 border border-gray-700">
        <!-- Titre du match -->
        <h2 class="text-2xl font-extrabold text-center mb-6 text-gray-100">
            {{ $match->homeTeam->university->name }} vs {{ $match->awayTeam->university->name }}
        </h2>

        <!-- Affichage des scores avec logos -->
        <div class="flex justify-center items-center gap-8 mb-8">
            <!-- Équipe à domicile -->
            <div class="w-1/3 flex flex-col items-end text-right">
                <div class="flex items-center space-x-4">
                    @if(isset($match->homeTeam->logo_url) && $match->homeTeam->logo_url)
                        <img src="{{ asset('storage/' . $match->homeTeam->logo_url) }}" alt="Logo {{ $match->homeTeam->university->short_name }}" class="h-16 w-16 object-contain transition-transform duration-300 hover:scale-110">
                    @else
                        <i class="fas fa-shield-alt text-blue-400 h-16 w-16 text-6xl flex items-center justify-center transition-transform duration-300 hover:scale-110"></i>
                    @endif
                    <p class="text-5xl font-extrabold text-blue-400 score-display" id="home-score">{{ $match->home_score }}</p>
                </div>
                <p class="text-lg font-medium text-gray-300 mt-2">{{ $match->homeTeam->university->short_name }}</p>
            </div>

            <!-- Séparateur -->
            <span class="text-5xl font-extrabold text-gray-500 mx-4">-</span>

            <!-- Équipe à l'extérieur -->
            <div class="w-1/3 flex flex-col items-start text-left">
                <div class="flex items-center space-x-4">
                    <p class="text-5xl font-extrabold text-blue-400 score-display" id="away-score">{{ $match->away_score }}</p>
                    @if(isset($match->awayTeam->logo_url) && $match->awayTeam->logo_url)
                        <img src="{{ asset('storage/' . $match->awayTeam->logo_url) }}" alt="Logo {{ $match->awayTeam->university->short_name }}" class="h-16 w-16 object-contain transition-transform duration-300 hover:scale-110">
                    @else
                        <i class="fas fa-shield-alt text-blue-400 h-16 w-16 text-6xl flex items-center justify-center transition-transform duration-300 hover:scale-110"></i>
                    @endif
                </div>
                <p class="text-lg font-medium text-gray-300 mt-2">{{ $match->awayTeam->university->short_name }}</p>
            </div>
        </div>

        <!-- Statut et mise à jour -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <p class="text-xl font-bold
                    @if($match->isLive()) text-red-400
                    @elseif($match->isHalftime()) text-yellow-400
                    @elseif($match->isFinished()) text-green-400
                    @else text-gray-400 @endif">
                    Statut : {{ ucfirst($match->status) }}
                </p>
            </div>

            <div class="flex space-x-4">
                <form action="{{ route('admin.live.update_status', $match) }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    <select name="status" class="bg-gray-700 text-white border-none rounded px-3 py-2">
                        <option value="scheduled" {{ $match->status === 'scheduled' ? 'selected' : '' }}>Planifié</option>
                        <option value="live" {{ $match->status === 'live' ? 'selected' : '' }}>En Direct</option>
                        <option value="halftime" {{ $match->status === 'halftime' ? 'selected' : '' }}>Mi-temps</option>
                        <option value="finished" {{ $match->status === 'finished' ? 'selected' : '' }}>Terminé</option>
                    </select>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                        Mettre à jour
                    </button>
                </form>

                <div class="flex items-center space-x-2">
                    <button type="button" id="pause-timer-btn" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-2 rounded text-sm">
                        Pause chrono
                    </button>
                    <button type="button" id="resume-timer-btn" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-sm">
                        Reprendre chrono
                    </button>
                </div>
                
                @if($match->status === 'finished')
                <a href="{{ route('admin.matches.stats.edit', $match) }}" class="text-blue-500 hover:text-blue-400 text-sm ml-4">
                    <i class="fas fa-chart-bar mr-1"></i> Statistiques Manuelles
                </a>
                @endif
            </div>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            @php
                $stats = [
                    ['name' => 'shots', 'label' => 'Tirs', 'home' => $match->home_shots ?? 0, 'away' => $match->away_shots ?? 0, 'icon' => '⚽'],
                    ['name' => 'shots_on_target', 'label' => 'Tirs cadrés', 'home' => $match->home_shots_on_target ?? 0, 'away' => $match->away_shots_on_target ?? 0, 'icon' => '🎯'],
                    ['name' => 'corners', 'label' => 'Corners', 'home' => $match->home_corners ?? 0, 'away' => $match->away_corners ?? 0, 'icon' => '🏁'],
                    ['name' => 'fouls', 'label' => 'Fautes', 'home' => $match->home_fouls ?? 0, 'away' => $match->away_fouls ?? 0, 'icon' => '⚠️'],
                    ['name' => 'offsides', 'label' => 'Hors-jeux', 'home' => $match->home_offsides ?? 0, 'away' => $match->away_offsides ?? 0, 'icon' => '🚫'],
                    ['name' => 'saves', 'label' => 'Arrêts', 'home' => $match->home_saves ?? 0, 'away' => $match->away_saves ?? 0, 'icon' => '🧤'],
                    ['name' => 'free_kicks', 'label' => 'Coups francs', 'home' => $match->home_free_kicks ?? 0, 'away' => $match->away_free_kicks ?? 0, 'icon' => '🎯'],
                    ['name' => 'throw_ins', 'label' => 'Touches', 'home' => $match->home_throw_ins ?? 0, 'away' => $match->away_throw_ins ?? 0, 'icon' => '🤲'],
                ];
            @endphp

            @foreach($stats as $stat)
            <div class="bg-gray-700 rounded-lg p-3 stat-card">
                <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                    <span>{{ $stat['label'] }}</span>
                    <span class="text-lg">{{ $stat['icon'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <button @click="updateStat('{{ $stat['name'] }}', 'home', 'decrement')"
                                class="bg-red-700 text-white rounded w-7 h-7 flex items-center justify-center text-xs hover:bg-red-600 transition action-button"
                                :disabled="home_{{ $stat['name'] }} <= 0">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span x-text="home_{{ $stat['name'] }}" class="font-bold w-6 text-center text-lg">{{ $stat['home'] }}</span>
                        <button @click="updateStat('{{ $stat['name'] }}', 'home', 'increment')"
                                class="bg-green-700 text-white rounded w-7 h-7 flex items-center justify-center text-xs hover:bg-green-600 transition action-button">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <span class="text-gray-400 mx-1">|</span>

                    <div class="flex items-center space-x-2">
                        <button @click="updateStat('{{ $stat['name'] }}', 'away', 'decrement')"
                                class="bg-red-700 text-white rounded w-7 h-7 flex items-center justify-center text-xs hover:bg-red-600 transition action-button"
                                :disabled="away_{{ $stat['name'] }} <= 0">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span x-text="away_{{ $stat['name'] }}" class="font-bold w-6 text-center text-lg">{{ $stat['away'] }}</span>
                        <button @click="updateStat('{{ $stat['name'] }}', 'away', 'increment')"
                                class="bg-green-700 text-white rounded w-7 h-7 flex items-center justify-center text-xs hover:bg-green-600 transition action-button">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Contenu principal en deux colonnes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Colonnes des événements et historique -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Ajouter un événement -->
            <div class="bg-gray-800 shadow-lg rounded-lg border border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-700 bg-gray-700">
                    <h3 class="text-lg font-semibold text-blue-300">Ajouter un Événement</h3>
                </div>
                <div class="p-5">
                    <form action="{{ route('admin.live.add_event', $match) }}" method="POST" class="space-y-5" id="event-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2">
                                <label for="event_type" class="block text-sm font-medium text-gray-300">Type d'événement</label>
                                <select name="event_type" id="event_type" class="form-select block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-md py-2 px-3" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="goal">But ⚽</option>
                                    <option value="penalty_goal">But (Penalty)</option>
                                    <option value="own_goal">But C.S.C.</option>
                                    <option value="yellow_card">Carton Jaune 🟨</option>
                                    <option value="red_card">Carton Rouge 🟥</option>
                                    <option value="second_yellow">Second Carton Jaune 🟨🟨</option>
                                    <option value="substitution_in">Remplacement 🔄</option>
                                    <option value="injury">Blessure ⚕️</option>
                                    <option value="penalty_missed">Penalty manqué ❌</option>
                                    <option value="big_chance_missed">Grosse occasion manquée 😱</option>
                                </select>
                            </div>

                            <div class="space-y-2" id="team-selector-container">
                                <label for="event_team_id" class="block text-sm font-medium text-gray-300">Équipe</label>
                                <select name="team_id" id="event_team_id" class="form-select block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-md py-2 px-3" required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="{{ $match->home_team_id }}">{{ $match->homeTeam->university->short_name }}</option>
                                    <option value="{{ $match->away_team_id }}">{{ $match->awayTeam->university->short_name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-2" id="player-main-container">
                                <label for="player_id" class="block text-sm font-medium text-gray-300">Joueur</label>
                                <select name="player_id" id="player_id" class="form-select block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-md py-2 px-3" required>
                                    <option value="">-- Sélectionner d'abord le type et l'équipe --</option>
                                </select>
                                <p id="player-help-text" class="text-xs text-gray-400 mt-1"></p>
                            </div>

                            <div class="space-y-2" id="minute-container">
                                <label for="minute" class="block text-sm font-medium text-gray-300">Minute</label>
                                <input type="number" name="minute" id="minute" min="1" max="120"
                                       value="1" class="form-input block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-md py-2 px-3" required>
                            </div>
                        </div>

                        <div class="space-y-2" id="assist-player-container" style="display: none;">
                            <label for="assist_player_id" class="block text-sm font-medium text-gray-300">Passeur (Optionnel)</label>
                            <select name="assist_player_id" id="assist_player_id" class="form-select block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-md py-2 px-3">
                                <option value="">-- Pas d'assistance --</option>
                            </select>
                        </div>

                        <div class="space-y-2" id="out-player-container" style="display: none;">
                            <label for="out_player_id" class="block text-sm font-medium text-gray-300">Joueur sortant</label>
                            <select name="out_player_id" id="out_player_id" class="form-select block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-md py-2 px-3">
                                <option value="">-- Sélectionner --</option>
                            </select>
                        </div>

                        <div class="pt-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-5 rounded shadow-md w-full transition">
                                <i class="fas fa-plus mr-2"></i> Enregistrer l'événement
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Historique des événements -->
            <div class="bg-gray-800 shadow-lg rounded-lg border border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-700 bg-gray-700">
                    <h3 class="text-lg font-semibold text-blue-300">Historique des Événements</h3>
                </div>
                <div class="p-5">
                    <div id="event-history-list" class="max-h-[500px] overflow-y-auto space-y-3">
                        @if($events->isEmpty())
                            <p class="text-gray-400 text-center py-6">Aucun événement enregistré pour le moment.</p>
                        @else
                            @foreach($events->sortByDesc('minute') as $event)
                                @php
                                    $bgColor = '';
                                    $icon = '';
                                    $textColor = '';
                                    $eventLabel = '';

                                    switch($event->event_type) {
                                        case 'goal':
                                        case 'penalty_goal':
                                            $bgColor = 'bg-green-900/50 border-green-500/30';
                                            $icon = '⚽';
                                            $textColor = 'text-green-300';
                                            $eventLabel = 'BUT!';
                                            break;
                                        case 'own_goal':
                                            $bgColor = 'bg-yellow-900/50 border-yellow-500/30';
                                            $icon = '🥅';
                                            $textColor = 'text-yellow-300';
                                            $eventLabel = 'BUT C.S.C.';
                                            break;
                                        case 'yellow_card':
                                            $bgColor = 'bg-yellow-900/50 border-yellow-500/30';
                                            $icon = '🟨';
                                            $textColor = 'text-yellow-300';
                                            $eventLabel = 'Carton Jaune';
                                            break;
                                        case 'second_yellow':
                                            $bgColor = 'bg-yellow-900/50 border-yellow-500/30';
                                            $icon = '🟨🟨';
                                            $textColor = 'text-yellow-300';
                                            $eventLabel = 'Second Jaune';
                                            break;
                                        case 'red_card':
                                            $bgColor = 'bg-red-900/50 border-red-500/30';
                                            $icon = '🟥';
                                            $textColor = 'text-red-300';
                                            $eventLabel = 'Carton Rouge';
                                            break;
                                        case 'substitution_in':
                                            $bgColor = 'bg-blue-900/50 border-blue-500/30';
                                            $icon = '🔄';
                                            $textColor = 'text-blue-300';
                                            $eventLabel = 'Remplacement';
                                            break;
                                        case 'injury':
                                            $bgColor = 'bg-purple-900/50 border-purple-500/30';
                                            $icon = '⚕️';
                                            $textColor = 'text-purple-300';
                                            $eventLabel = 'Blessure';
                                            break;
                                        case 'penalty_missed':
                                            $bgColor = 'bg-orange-900/50 border-orange-500/30';
                                            $icon = '❌';
                                            $textColor = 'text-orange-300';
                                            $eventLabel = 'Penalty manqué';
                                            break;
                                        case 'big_chance_missed':
                                            $bgColor = 'bg-orange-900/50 border-orange-500/30';
                                            $icon = '😱';
                                            $textColor = 'text-orange-300';
                                            $eventLabel = 'Occasion manquée';
                                            break;
                                    }

                                    $teamBorder = $event->team_id == $match->home_team_id ? 'border-l-4 border-blue-500' : 'border-l-4 border-red-500';
                                @endphp

                                <div class="p-4 rounded-lg flex justify-between items-center {{ $bgColor }} {{ $teamBorder }}">
                                    <div class="flex items-center">
                                        <span class="font-mono text-gray-300 text-sm mr-3">{{ $event->minute }}'</span>
                                        <span class="{{ $textColor }} text-lg mr-2">{{ $icon }}</span>
                                        <div class="text-gray-100">
                                            <span class="font-medium {{ $textColor }}">{{ $eventLabel }}</span>
                                            <span class="ml-1">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span>

                                            @if(in_array($event->event_type, ['goal', 'penalty_goal'], true) && $event->assistPlayer)
                                                <div class="text-xs text-gray-300 mt-1">
                                                    Assisté par {{ $event->assistPlayer->full_name ?? 'Joueur inconnu' }}
                                                </div>
                                            @elseif($event->event_type == 'substitution_in' && $event->outPlayer)
                                                <div class="text-xs text-gray-300 mt-1">
                                                    Remplace {{ $event->outPlayer->full_name ?? 'Joueur inconnu' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center space-x-3">
                                        <span class="text-xs text-gray-300">
                                            {{ $event->team_id == $match->home_team_id ? $match->homeTeam->university->short_name : $match->awayTeam->university->short_name }}
                                        </span>

                                        <form action="{{ route('admin.live.delete_event', $event) }}" method="POST" onsubmit="return confirm('Supprimer cet événement ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300 p-1">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne des contrôles -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Contrôle du score -->
            <div class="bg-gray-800 shadow-lg rounded-lg border border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-700 bg-gray-700">
                    <h3 class="text-lg font-semibold text-blue-300">Contrôle du Score</h3>
                </div>
                <div class="p-5">
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-blue-900 rounded">
                                    @if(isset($match->homeTeam->logo_url) && $match->homeTeam->logo_url)
                                        <img src="{{ asset('storage/' . $match->homeTeam->logo_url) }}" alt="Logo {{ $match->homeTeam->university->short_name }}" class="w-8 h-8 object-contain">
                                    @else
                                        <i class="fas fa-shield-alt text-xs text-white"></i>
                                    @endif
                                </div>
                                <span class="font-medium">{{ $match->homeTeam->university->short_name }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button @click="updateStat('score', 'home', 'decrement')"
                                        class="bg-red-700 text-white w-10 h-10 rounded flex items-center justify-center hover:bg-red-600 transition"
                                        :disabled="parseInt(document.getElementById('admin-home-score').textContent) <= 0">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span id="admin-home-score" class="font-bold text-2xl w-10 text-center">{{ $match->home_score }}</span>
                                <button @click="updateStat('score', 'home', 'increment')"
                                        class="bg-green-700 text-white w-10 h-10 rounded flex items-center justify-center hover:bg-green-600 transition">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-green-900 rounded">
                                    @if(isset($match->awayTeam->logo_url) && $match->awayTeam->logo_url)
                                        <img src="{{ asset('storage/' . $match->awayTeam->logo_url) }}" alt="Logo {{ $match->awayTeam->university->short_name }}" class="w-8 h-8 object-contain">
                                    @else
                                        <i class="fas fa-shield-alt text-xs text-white"></i>
                                    @endif
                                </div>
                                <span class="font-medium">{{ $match->awayTeam->university->short_name }}</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button @click="updateStat('score', 'away', 'decrement')"
                                        class="bg-red-700 text-white w-10 h-10 rounded flex items-center justify-center hover:bg-red-600 transition"
                                        :disabled="parseInt(document.getElementById('admin-away-score').textContent) <= 0">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span id="admin-away-score" class="font-bold text-2xl w-10 text-center">{{ $match->away_score }}</span>
                                <button @click="updateStat('score', 'away', 'increment')"
                                        class="bg-green-700 text-white w-10 h-10 rounded flex items-center justify-center hover:bg-green-600 transition">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cartons rapides -->
            <div class="bg-gray-800 shadow-lg rounded-lg border border-gray-700 overflow-hidden">
                <div class="p-5 border-b border-gray-700 bg-gray-700">
                    <h3 class="text-lg font-semibold text-blue-300">Cartons Rapides</h3>
                </div>
                <div class="p-5">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-yellow-500 rounded text-sm">🟨</div>
                                <span class="font-medium">Carton Jaune</span>
                            </div>
                            <div class="flex space-x-3">
                                <button onclick="quickCard('yellow_card', 'home')"
                                        class="bg-yellow-700 text-white w-10 h-10 rounded flex items-center justify-center text-sm hover:bg-yellow-600 transition">
                                    {{ $match->homeTeam->university->short_name }}
                                </button>
                                <button onclick="quickCard('yellow_card', 'away')"
                                        class="bg-yellow-700 text-white w-10 h-10 rounded flex items-center justify-center text-sm hover:bg-yellow-600 transition">
                                    {{ $match->awayTeam->university->short_name }}
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-red-500 rounded text-sm">🟥</div>
                                <span class="font-medium">Carton Rouge</span>
                            </div>
                            <div class="flex space-x-3">
                                <button onclick="quickCard('red_card', 'home')"
                                        class="bg-red-700 text-white w-10 h-10 rounded flex items-center justify-center text-sm hover:bg-red-600 transition">
                                    {{ $match->homeTeam->university->short_name }}
                                </button>
                                <button onclick="quickCard('red_card', 'away')"
                                        class="bg-red-700 text-white w-10 h-10 rounded flex items-center justify-center text-sm hover:bg-red-600 transition">
                                    {{ $match->awayTeam->university->short_name }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction pour mettre à jour les statistiques - accessible globalement
    window.updateStat = function(statName, teamSide, action) {
        const url = '{{ route('admin.live.update_stats', $match) }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const alpineScope = this; // Contexte Alpine.js

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                stat_name: statName,
                team_side: teamSide,
                action: action
            })
        })
        .then(response => {
            if (response.status === 400) {
                return response.json().then(data => { throw new Error(data.message) });
            }
            if (!response.ok) {
                throw new Error('Erreur réseau ou du serveur.');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                if (statName === 'score') {
                    document.getElementById(`admin-${teamSide}-score`).textContent = data.new_value;
                    document.getElementById(`${teamSide}-score`).textContent = data.new_value;
                } else {
                    const key = `${teamSide}_${statName}`;
                    if (alpineScope && alpineScope[key] !== undefined) {
                        alpineScope[key] = data.new_value;
                    }
                }
            } else {
                alert('Erreur: ' + (data.message || 'Impossible de mettre à jour'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue: ' + error.message);
        });
    };

    // Cartons rapides
    function quickCard(type, team) {
        const eventTypeSelect = document.getElementById('event_type');
        const teamSelect = document.getElementById('event_team_id');

        eventTypeSelect.value = type;
        teamSelect.value = team === 'home' ? '{{ $match->home_team_id }}' : '{{ $match->away_team_id }}';

        const event = new Event('change');
        eventTypeSelect.dispatchEvent(event);
        teamSelect.dispatchEvent(event);

        document.getElementById('event-form').scrollIntoView({ behavior: 'smooth' });
    }

    // Données des joueurs pour le formulaire
    const HOME_PLAYERS = @json($homePlayersData);
    const AWAY_PLAYERS = @json($awayPlayersData);
    let HOME_STARTERS = @json($homeStartersData);
    let HOME_BENCH = @json($homeBenchData);
    let AWAY_STARTERS = @json($awayStartersData);
    let AWAY_BENCH = @json($awayBenchData);
    const LINEUP_SELECT_URL_TEMPLATE = "{{ route('admin.live.lineup.select', ['match' => $match->id, 'team' => 'TEAM_ID']) }}";
    const EVENTS_LIST_URL = "{{ route('admin.live.events.list', $match) }}";
    const EVENT_DELETE_URL_TEMPLATE = "{{ route('admin.live.delete_event', ['matchEvent' => 'EVENT_ID']) }}";

    function populatePlayerSelect(selectElement, players, defaultText, selectedPlayerId = null) {
        let options = `<option value="">${defaultText}</option>`;
        const sortedPlayers = Object.values(players).sort((a, b) => a.jersey - b.jersey);

        sortedPlayers.forEach(player => {
            const selected = player.id == selectedPlayerId ? ' selected' : '';
            options += `<option value="${player.id}"${selected}>#${player.jersey} ${player.name}</option>`;
        });

        selectElement.innerHTML = options;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const pauseTimerBtn = document.getElementById('pause-timer-btn');
        const resumeTimerBtn = document.getElementById('resume-timer-btn');

        function postTimerAction(url, successMessage) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la mise à jour du chrono.');
                }
                return response.json();
            })
            .then(data => {
                if (!data || !data.success) {
                    throw new Error(data?.message || 'Action impossible.');
                }
                alert(successMessage);
            })
            .catch(error => {
                console.error(error);
                alert(error.message);
            });
        }

        if (pauseTimerBtn) {
            pauseTimerBtn.addEventListener('click', () => {
                postTimerAction('{{ route('admin.match-timer.pause', $match) }}', 'Chrono mis en pause.');
            });
        }

        if (resumeTimerBtn) {
            resumeTimerBtn.addEventListener('click', () => {
                postTimerAction('{{ route('admin.match-timer.resume', $match) }}', 'Chrono repris.');
            });
        }

        const eventTypeSelect = document.getElementById('event_type');
        const teamSelect = document.getElementById('event_team_id');
        const playerMainSelect = document.getElementById('player_id');
        const assistContainer = document.getElementById('assist-player-container');
        const assistSelect = document.getElementById('assist_player_id');
        const outPlayerContainer = document.getElementById('out-player-container');
        const outPlayerSelect = document.getElementById('out_player_id');
        const playerHelpText = document.getElementById('player-help-text');

        const homeTeamId = '{{ $match->home_team_id }}';
        const awayTeamId = '{{ $match->away_team_id }}';

        let lastLineupFetchToken = 0;

        async function refreshLineupSelectData(teamId) {
            const fetchToken = ++lastLineupFetchToken;
            const url = LINEUP_SELECT_URL_TEMPLATE.replace('TEAM_ID', encodeURIComponent(teamId));

            try {
                const response = await fetch(url, {
                    headers: { 'Accept': 'application/json' },
                });

                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des compositions.');
                }

                const data = await response.json();
                if (fetchToken !== lastLineupFetchToken) {
                    return;
                }

                if (teamId == homeTeamId) {
                    HOME_STARTERS = data.starters || HOME_STARTERS;
                    HOME_BENCH = data.bench || HOME_BENCH;
                } else if (teamId == awayTeamId) {
                    AWAY_STARTERS = data.starters || AWAY_STARTERS;
                    AWAY_BENCH = data.bench || AWAY_BENCH;
                }
            } catch (error) {
                console.error(error);
            }
        }

        async function updateFormFields() {
            const eventType = eventTypeSelect.value;
            const teamId = teamSelect.value;
            const isHomeTeam = (teamId == homeTeamId);
            const players = teamId ? (isHomeTeam ? HOME_PLAYERS : AWAY_PLAYERS) : {};
            let starters = teamId ? (isHomeTeam ? HOME_STARTERS : AWAY_STARTERS) : {};
            let bench = teamId ? (isHomeTeam ? HOME_BENCH : AWAY_BENCH) : {};

            assistContainer.style.display = (eventType === 'goal' || eventType === 'penalty_goal') ? 'block' : 'none';
            outPlayerContainer.style.display = (eventType === 'substitution_in') ? 'block' : 'none';

            playerHelpText.textContent = '';
            const mainPlayerDefaultText = teamId ? '-- Sélectionner le joueur --' : '-- Sélectionner d\'abord l\'équipe --';

            if (teamId) {
                if (eventType === 'substitution_in') {
                    await refreshLineupSelectData(teamId);
                    starters = isHomeTeam ? HOME_STARTERS : AWAY_STARTERS;
                    bench = isHomeTeam ? HOME_BENCH : AWAY_BENCH;

                    playerHelpText.textContent = "Sélectionnez le joueur qui ENTRE";
                    populatePlayerSelect(playerMainSelect, bench, '-- Sélectionner le joueur entrant --');
                    populatePlayerSelect(outPlayerSelect, starters, '-- Sélectionner le joueur sortant --');
                    populatePlayerSelect(assistSelect, {}, '-- Pas d\'assistance --');
                } else {
                    await refreshLineupSelectData(teamId);
                    starters = isHomeTeam ? HOME_STARTERS : AWAY_STARTERS;
                    const hasStarters = starters && Object.keys(starters).length > 0;
                    const onFieldPlayers = hasStarters ? starters : players;

                    populatePlayerSelect(playerMainSelect, onFieldPlayers, mainPlayerDefaultText);

                    if (eventType === 'goal' || eventType === 'penalty_goal') {
                        playerHelpText.textContent = "Sélectionnez le buteur";
                        populatePlayerSelect(assistSelect, onFieldPlayers, '-- Pas d\'assistance --');
                        populatePlayerSelect(outPlayerSelect, {}, '-- Sélectionner --');
                    } else {
                        if (eventType === 'own_goal') {
                            playerHelpText.textContent = "Sélectionnez le joueur qui a marqué contre son camp";
                        } else if (eventType === 'yellow_card' || eventType === 'red_card' || eventType === 'second_yellow') {
                            playerHelpText.textContent = "Sélectionnez le joueur cartonné";
                        } else if (eventType === 'injury') {
                            playerHelpText.textContent = "Sélectionnez le joueur blessé";
                        }
                        populatePlayerSelect(outPlayerSelect, {}, '-- Sélectionner --');
                        populatePlayerSelect(assistSelect, {}, '-- Pas d\'assistance --');
                    }
                }
            } else {
                populatePlayerSelect(playerMainSelect, {}, '-- Sélectionner d\'abord l\'équipe --');
                populatePlayerSelect(assistSelect, {}, '-- Pas d\'assistance --');
                populatePlayerSelect(outPlayerSelect, {}, '-- Sélectionner --');
            }
        }

        eventTypeSelect.addEventListener('change', updateFormFields);
        teamSelect.addEventListener('change', updateFormFields);

        updateFormFields();

        let lastEventsSignature = null;

        function getEventStyle(eventType) {
            switch (eventType) {
                case 'goal':
                case 'penalty_goal':
                    return { bg: 'bg-green-900/50 border-green-500/30', icon: '⚽', text: 'text-green-300', label: 'BUT!' };
                case 'own_goal':
                    return { bg: 'bg-yellow-900/50 border-yellow-500/30', icon: '🥅', text: 'text-yellow-300', label: 'BUT C.S.C.' };
                case 'yellow_card':
                    return { bg: 'bg-yellow-900/50 border-yellow-500/30', icon: '🟨', text: 'text-yellow-300', label: 'Carton Jaune' };
                case 'second_yellow':
                    return { bg: 'bg-yellow-900/50 border-yellow-500/30', icon: '🟨🟨', text: 'text-yellow-300', label: 'Second Jaune' };
                case 'red_card':
                    return { bg: 'bg-red-900/50 border-red-500/30', icon: '🟥', text: 'text-red-300', label: 'Carton Rouge' };
                case 'substitution_in':
                    return { bg: 'bg-blue-900/50 border-blue-500/30', icon: '🔄', text: 'text-blue-300', label: 'Remplacement' };
                case 'injury':
                    return { bg: 'bg-purple-900/50 border-purple-500/30', icon: '⚕️', text: 'text-purple-300', label: 'Blessure' };
                case 'penalty_missed':
                    return { bg: 'bg-orange-900/50 border-orange-500/30', icon: '❌', text: 'text-orange-300', label: 'Penalty manqué' };
                case 'big_chance_missed':
                    return { bg: 'bg-orange-900/50 border-orange-500/30', icon: '😱', text: 'text-orange-300', label: 'Occasion manquée' };
                default:
                    return { bg: 'bg-gray-800/50 border-gray-600/30', icon: '•', text: 'text-gray-300', label: 'Événement' };
            }
        }

        function renderEvents(events) {
            const container = document.getElementById('event-history-list');
            if (!container) return;

            if (!events || events.length === 0) {
                container.innerHTML = '<p class="text-gray-400 text-center py-6">Aucun événement enregistré pour le moment.</p>';
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const html = events.map(event => {
                const style = getEventStyle(event.event_type);
                const teamBorder = event.is_home_team ? 'border-l-4 border-blue-500' : 'border-l-4 border-red-500';
                const assistHtml = (event.event_type === 'goal' || event.event_type === 'penalty_goal') && event.assist_name
                    ? `<div class="text-xs text-gray-300 mt-1">Assisté par ${event.assist_name}</div>`
                    : '';
                const outHtml = event.event_type === 'substitution_in' && event.out_player_name
                    ? `<div class="text-xs text-gray-300 mt-1">Remplace ${event.out_player_name}</div>`
                    : '';
                const deleteUrl = EVENT_DELETE_URL_TEMPLATE.replace('EVENT_ID', event.id);

                return `
                    <div class="p-4 rounded-lg flex justify-between items-center ${style.bg} ${teamBorder}">
                        <div class="flex items-center">
                            <span class="font-mono text-gray-300 text-sm mr-3">${event.minute}'</span>
                            <span class="${style.text} text-lg mr-2">${style.icon}</span>
                            <div class="text-gray-100">
                                <span class="font-medium ${style.text}">${style.label}</span>
                                <span class="ml-1">${event.player_name}</span>
                                ${assistHtml}
                                ${outHtml}
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="text-xs text-gray-300">${event.team_short_name}</span>
                            <form action="${deleteUrl}" method="POST" onsubmit="return confirm('Supprimer cet événement ?')">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-400 hover:text-red-300 p-1">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = html;
        }

        async function refreshEventsList() {
            try {
                const response = await fetch(EVENTS_LIST_URL, {
                    headers: { 'Accept': 'application/json' },
                });
                if (!response.ok) {
                    throw new Error('Erreur lors du chargement des événements.');
                }

                const data = await response.json();
                if (!data?.success) {
                    throw new Error('Réponse invalide.');
                }

                const signature = JSON.stringify(data.events || []);
                if (signature !== lastEventsSignature) {
                    lastEventsSignature = signature;
                    renderEvents(data.events || []);
                }
            } catch (error) {
                console.error(error);
            }
        }

        refreshEventsList();
        setInterval(refreshEventsList, 8000);
    });
    
    // Activer la notification de synchronisation
    window.addEventListener('load', function() {
        const syncNotification = document.getElementById('sync-notification');
        if (syncNotification) {
            syncNotification.style.opacity = '1';
            syncNotification.style.pointerEvents = 'auto';
        }
    });
    
    // Mise à jour en temps réel pour la synchronisation
    function showUpdateNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2 z-50 slide-in-right';
        notification.innerHTML = `
            <i class="fas fa-check-circle"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Fonction pour mettre à jour l'interface après une mise à jour réussie
    function updateUIAfterSuccess(statName, teamSide, newValue) {
        if (statName === 'score') {
            const scoreElement = document.getElementById(`${teamSide}-score`);
            if (scoreElement) {
                scoreElement.classList.add('pulse-button');
                setTimeout(() => scoreElement.classList.remove('pulse-button'), 1000);
            }
        }
        
        showUpdateNotification(`Mise à jour réussie: ${statName} pour ${teamSide} est maintenant ${newValue}`);
    }
    
    // Fonction pour les cartes rapides améliorée
    function quickCard(type, team) {
        const eventTypeSelect = document.getElementById('event_type');
        const teamSelect = document.getElementById('event_team_id');
        
        eventTypeSelect.value = type;
        teamSelect.value = team === 'home' ? '{{ $match->home_team_id }}' : '{{ $match->away_team_id }}';
        
        const event = new Event('change');
        eventTypeSelect.dispatchEvent(event);
        teamSelect.dispatchEvent(event);
        
        document.getElementById('event-form').scrollIntoView({ behavior: 'smooth' });
        
        // Ajouter une animation pour attirer l'attention
        const form = document.getElementById('event-form');
        form.classList.add('border-2', 'border-blue-500');
        setTimeout(() => form.classList.remove('border-2', 'border-blue-500'), 2000);
    }
    
    // Synchronisation en temps réel avec le frontend
    function syncWithFrontend() {
        // Cette fonction sera appelée après chaque mise à jour pour s'assurer
        // que les données sont synchronisées avec la vue publique
    }
</script>
@endsection
