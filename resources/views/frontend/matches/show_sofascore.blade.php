@extends('layouts.app')

@section('title', 'Match - ' . ($match->homeTeam->university->short_name ?? 'Équipe') . ' vs ' . ($match->awayTeam->university->short_name ?? 'Équipe'))

@section('content')
<style>
    /* Styles Sofascore-like avec thème sombre cohérent */
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&family=Roboto+Condensed:wght@400;700&display=swap');
    
    .sofascore-container {
        font-family: 'Roboto', sans-serif;
        animation: fadeIn 0.5s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .match-header-sofascore {
        background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%);
        color: white;
        padding: 30px 0;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(26, 115, 232, 0.3);
        animation: slideDown 0.6s ease-out;
    }
    
    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    .team-info-sofascore {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 20px;
        position: relative;
        z-index: 1;
    }
    
    .team-logo-sofascore {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: contain;
        border: 4px solid white;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
        transition: all 0.3s ease;
        animation: bounceIn 0.8s ease-out;
    }
    
    @keyframes bounceIn {
        0% { transform: scale(0.5); opacity: 0; }
        50% { transform: scale(1.1); }
        70% { transform: scale(0.9); }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .team-logo-sofascore:hover {
        transform: scale(1.05) rotate(5deg);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }
    
    .score-sofascore {
        font-family: 'Roboto Condensed', sans-serif;
        font-size: 4.5rem;
        font-weight: 900;
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
        background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: scorePulse 2s infinite alternate;
    }
    
    @keyframes scorePulse {
        0% { text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3); }
        100% { text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2), 0 0 20px rgba(255, 255, 255, 0.3); }
    }
    
    .match-status-sofascore {
        background: linear-gradient(135deg, #e94560 0%, #d23f57 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: bold;
        font-size: 0.9rem;
        animation: pulse 1.5s infinite, bounce 0.5s ease-out;
        display: inline-block;
        margin-bottom: 12px;
        box-shadow: 0 4px 12px rgba(233, 69, 96, 0.4);
        position: relative;
        overflow: hidden;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(233, 69, 96, 0.7); }
        70% { box-shadow: 0 0 0 12px rgba(233, 69, 96, 0); }
        100% { box-shadow: 0 0 0 0 rgba(233, 69, 96, 0); }
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    .tab-container-sofascore {
        display: flex;
        border-bottom: 2px solid #e0e0e0;
        margin-bottom: 25px;
        background: #1f2937;
        border-radius: 12px 12px 0 0;
        padding: 8px 8px 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow-x: auto;
        animation: tabContainerAppear 0.5s ease-out;
    }
    
    @keyframes tabContainerAppear {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .tab-sofascore {
        padding: 14px 28px;
        cursor: pointer;
        border: none;
        background: transparent;
        font-weight: 600;
        color: #9ca3af;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-bottom: 3px solid transparent;
        position: relative;
        white-space: nowrap;
    }
    
    .tab-sofascore::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 50%;
        width: 0;
        height: 3px;
        background: #3b82f6;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }
    
    .tab-sofascore:hover {
        color: #3b82f6;
        transform: translateY(-2px);
    }
    
    .tab-sofascore:hover::after {
        width: 100%;
    }
    
    .tab-sofascore.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
    }
    
    .tab-sofascore.active::after {
        width: 100%;
    }
    
    .tab-content-sofascore {
        display: none;
        animation: tabContentFadeIn 0.4s ease-out;
    }
    
    @keyframes tabContentFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .tab-content-sofascore.active {
        display: block;
    }
    
    .events-section-sofascore {
        background: #111827;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        animation: slideInRight 0.6s ease-out;
    }
    
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(30px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .event-item-sofascore {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #374151;
        animation: eventAppear 0.4s ease-out backwards;
        opacity: 0;
        transform: translateX(-20px);
    }
    
    @keyframes eventAppear {
        to { opacity: 1; transform: translateX(0); }
    }
    
    .event-item-sofascore.new {
        animation: newEventPulse 1.5s ease-out;
        background: linear-gradient(90deg, rgba(76, 175, 80, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
    }
    
    @keyframes newEventPulse {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(10px); }
    }
    
    .event-icon-sofascore {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-weight: bold;
        color: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .event-icon-sofascore::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: inherit;
        opacity: 0.8;
        transform: scale(1.2);
        filter: blur(5px);
    }
    
    .event-goal { 
        background: linear-gradient(135deg, #10b981, #059669);
        animation: goalPulse 1s ease-out;
    }
    
    @keyframes goalPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }
    
    .event-card { 
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .event-sub { 
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }
    
    .event-minute-sofascore {
        font-weight: bold;
        color: #9ca3af;
        min-width: 45px;
        text-align: right;
        margin-right: 12px;
        font-size: 1.1rem;
        font-family: 'Roboto Condensed', sans-serif;
    }
    
    .event-description-sofascore {
        flex: 1;
        color: #d1d5db;
        transition: all 0.2s ease;
    }
    
    .event-item-sofascore:hover .event-description-sofascore {
        color: #3b82f6;
        transform: translateX(5px);
    }
    
    .player-name-sofascore {
        font-weight: 600;
        color: #3b82f6;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .player-name-sofascore::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: #3b82f6;
        transform: scaleX(0);
        transition: transform 0.2s ease;
    }
    
    .event-item-sofascore:hover .player-name-sofascore::after {
        transform: scaleX(1);
    }
    
    .lineup-section-sofascore {
        background: #111827;
        border-radius: 12px;
        padding: 25px;
        margin: 20px 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.8s ease-out;
    }
    
    /* Terrain de football virtuel */
    .football-field {
        position: relative;
        width: 100%;
        height: 400px;
        background: linear-gradient(to bottom, #22c55e, #16a34a);
        border-radius: 12px;
        overflow: hidden;
        margin: 20px 0;
    }
    
    .field-lines {
        position: absolute;
        width: 100%;
        height: 100%;
    }
    
    .field-lines::before,
    .field-lines::after {
        content: '';
        position: absolute;
        background: white;
    }
    
    .field-lines::before {
        width: 100%;
        height: 2px;
        top: 50%;
        left: 0;
    }
    
    .field-lines::after {
        width: 2px;
        height: 100%;
        left: 50%;
        top: 0;
    }
    
    .field-circle {
        position: absolute;
        width: 80px;
        height: 80px;
        border: 2px solid white;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .field-rectangle {
        position: absolute;
        width: 20px;
        height: 60px;
        border: 2px solid white;
        top: 50%;
        left: 10%;
        transform: translateY(-50%);
    }
    
    .field-rectangle.right {
        left: auto;
        right: 10%;
    }
    
    .player-position {
        position: absolute;
        width: 40px;
        height: 40px;
        background: #3b82f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .player-position:hover {
        transform: scale(1.2);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5);
    }
    
    .player-position.away {
        background: #ef4444;
    }
    
    .player-info-tooltip {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #1f2937;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.8rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    .player-position:hover .player-info-tooltip {
        opacity: 1;
        visibility: visible;
        bottom: 120%;
    }
    
    .match-info-section-sofascore {
        background: #111827;
        border-radius: 12px;
        padding: 20px;
        margin: 20px 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .info-row-sofascore {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #374151;
    }
    
    .info-label-sofascore {
        color: #9ca3af;
        font-weight: 500;
    }
    
    .info-value-sofascore {
        color: #d1d5db;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .team-info-sofascore {
            flex-direction: column;
            gap: 20px;
        }
        
        .score-sofascore {
            font-size: 3.5rem;
        }
        
        .tab-container-sofascore {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        
        .tab-sofascore {
            padding: 12px 20px;
            font-size: 0.9rem;
        }
    }
    
    /* Animation de chargement */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        animation: fadeOut 0.5s ease-out 1.5s forwards;
    }
    
    @keyframes fadeOut {
        to { opacity: 0; visibility: hidden; }
    }
    
    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 6px solid rgba(255, 255, 255, 0.3);
        border-top: 6px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .loading-text {
        color: white;
        font-weight: 600;
        margin-top: 20px;
        font-size: 1.2rem;
        animation: pulse 1.5s infinite;
    }
</style>

<div class="sofascore-container">
    <!-- Animation de chargement -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="loading-spinner"></div>
            <div class="loading-text mt-4">Chargement du match...</div>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header avec score et statut -->
        <div class="match-header-sofascore">
            <div class="team-info-sofascore">
                <div class="text-center">
                    @if($match->homeTeam->university->logo ?? false)
                        <img src="{{ asset('storage/' . $match->homeTeam->university->logo) }}" 
                             alt="{{ $match->homeTeam->university->name ?? 'Équipe' }}" 
                             class="team-logo-sofascore">
                    @else
                        <div class="team-logo-sofascore bg-white flex items-center justify-center">
                            <span class="text-2xl font-bold text-blue-600">{{ strtoupper(substr($match->homeTeam->university->short_name ?? 'HOME', 0, 2)) }}</span>
                        </div>
                    @endif
                    <div class="mt-2 font-bold text-lg">{{ $match->homeTeam->university->short_name ?? 'Équipe' }}</div>
                </div>
                
                <div class="text-center">
                    <div class="match-status-sofascore mb-2">
                        @if($match->status === 'live')
                            EN DIRECT
                        @elseif($match->status === 'halftime')
                            MI-TEMPS
                        @elseif($match->status === 'finished')
                            TERMINÉ
                        @elseif($match->status === 'scheduled')
                            À VENIR
                        @else
                            {{ strtoupper($match->status) }}
                        @endif
                    </div>
                    <div class="score-sofascore">
                        {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                    </div>
                    <div class="text-sm mt-2 opacity-90">
                        {{ $match->match_date->format('d/m/Y H:i') }}
                    </div>
                    <div class="text-lg mt-2 font-bold" id="elapsed-time" style="{{ in_array($match->status, ['live', 'halftime']) ? '' : 'display:none;' }}">
                        {{ $match->getFormattedTime() }}
                    </div>
                </div>
                
                <div class="text-center">
                    @if($match->awayTeam->university->logo ?? false)
                        <img src="{{ asset('storage/' . $match->awayTeam->university->logo) }}" 
                             alt="{{ $match->awayTeam->university->name ?? 'Équipe' }}" 
                             class="team-logo-sofascore">
                    @else
                        <div class="team-logo-sofascore bg-white flex items-center justify-center">
                            <span class="text-2xl font-bold text-blue-600">{{ strtoupper(substr($match->awayTeam->university->short_name ?? 'AWAY', 0, 2)) }}</span>
                        </div>
                    @endif
                    <div class="mt-2 font-bold text-lg">{{ $match->awayTeam->university->short_name ?? 'Équipe' }}</div>
                </div>
            </div>
        </div>

        <!-- Onglets de navigation -->
        <div class="tab-container-sofascore">
            <button class="tab-sofascore active" onclick="showTab('overview')">Aperçu</button>
            <button class="tab-sofascore" onclick="showTab('stats')">Statistiques</button>
            <button class="tab-sofascore" onclick="showTab('lineups')">Compositions</button>
            <button class="tab-sofascore" onclick="showTab('events')">Événements</button>
            <button class="tab-sofascore" onclick="showTab('info')">Infos</button>
        </div>

        <!-- Contenu des onglets -->
        <div id="overview" class="tab-content-sofascore active">
            <div class="events-section-sofascore">
                <h3 class="text-xl font-bold mb-4 text-white">Derniers événements</h3>
                @if($match->matchEvents->count() > 0)
                    @foreach($match->matchEvents->sortByDesc('minute')->take(5) as $event)
                        <div class="event-item-sofascore" data-event-id="{{ $event->id }}">
                            <div class="event-minute-sofascore">{{ $event->minute }}'</div>
                            <div class="event-icon-sofascore 
                                @if($event->event_type === 'goal') event-goal
                                @elseif(in_array($event->event_type, ['yellow_card', 'red_card'])) event-card
                                @elseif($event->event_type === 'substitution_in') event-sub
                                @endif
                            ">
                                @if($event->event_type === 'goal') ⚽
                                @elseif($event->event_type === 'yellow_card') ⚠
                                @elseif($event->event_type === 'red_card') ❌
                                @elseif($event->event_type === 'substitution_in') ↔
                                @endif
                            </div>
                            <div class="event-description-sofascore">
                                @if($event->event_type === 'goal')
                                    <span class="player-name-sofascore">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span> 
                                    a marqué
                                    @if($event->assistPlayer)
                                        (assist: {{ $event->assistPlayer->full_name }})
                                    @endif
                                @elseif($event->event_type === 'yellow_card')
                                    <span class="player-name-sofascore">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span> 
                                    a reçu un carton jaune
                                @elseif($event->event_type === 'red_card')
                                    <span class="player-name-sofascore">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span> 
                                    a reçu un carton rouge
                                @elseif($event->event_type === 'substitution_in')
                                    <span class="player-name-sofascore">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span> 
                                    remplace {{ $event->outPlayer->full_name ?? 'Joueur inconnu' }}
                                @endif
                                pour {{ $event->team->university->short_name ?? 'Équipe' }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-400 text-center py-4">Aucun événement enregistré pour ce match.</p>
                @endif
            </div>
        </div>

        <!-- Onglet Compositions simplifié -->
        <div id="lineups" class="tab-content-sofascore">
            <div class="lineup-section-sofascore">
                <h3 class="text-xl font-bold mb-6 text-white">Compositions d'équipe</h3>
                
                <!-- Équipe à domicile -->
                <div class="mb-8">
                    <h4 class="text-lg font-bold mb-4 text-white flex items-center">
                        @if($match->homeTeam->university->logo)
                            <img src="{{ asset('storage/' . $match->homeTeam->university->logo) }}" 
                                 alt="{{ $match->homeTeam->university->name ?? 'Équipe' }}" 
                                 class="h-8 w-8 mr-2 rounded-full">
                        @else
                            <i class="fas fa-shield-alt text-blue-500 mr-2"></i>
                        @endif
                        {{ $match->homeTeam->university->short_name ?? 'Équipe' }} 
                        ({{ $match->home_formation ?? 'Formation inconnue' }})</h4>
                    
                    <h5 class="text-md font-semibold mb-3 text-blue-400">Titulaires</h5>
                    <div class="space-y-3 mb-4">
                        @foreach($homeStarters as $player)
                        <div class="bg-gray-700 rounded-lg p-3 flex items-center justify-between hover:bg-gray-600 transition">
                            <div class="flex items-center space-x-3">
                                <div class="bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">
                                    {{ $player->player->jersey_number }}
                                </div>
                                <div>
                                    <div class="font-semibold text-white">{{ $player->player->full_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $player->player->position }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <h5 class="text-md font-semibold mt-4 mb-2 text-gray-300">Remplaçants</h5>
                    <div class="space-y-2">
                        @foreach($homeSubstitutes as $player)
                        <div class="bg-gray-700 rounded-lg p-2 flex items-center justify-between hover:bg-gray-600 transition">
                            <div class="flex items-center space-x-2">
                                <div class="bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">
                                    {{ $player->player->jersey_number }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-white">{{ $player->player->full_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $player->player->position }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 p-3 bg-gray-800 rounded-lg">
                        <strong>Entraîneur:</strong> {{ $match->home_coach ?? 'Non spécifié' }}
                    </div>
                </div>
                
                <!-- Équipe à l'extérieur -->
                <div class="mb-4">
                    <h4 class="text-lg font-bold mb-4 text-white flex items-center">
                        @if($match->awayTeam->university->logo)
                            <img src="{{ asset('storage/' . $match->awayTeam->university->logo) }}" 
                                 alt="{{ $match->awayTeam->university->name ?? 'Équipe' }}" 
                                 class="h-8 w-8 mr-2 rounded-full">
                        @else
                            <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                        @endif
                        {{ $match->awayTeam->university->short_name ?? 'Équipe' }} 
                        ({{ $match->away_formation ?? 'Formation inconnue' }})</h4>
                    
                    <h5 class="text-md font-semibold mb-3 text-green-400">Titulaires</h5>
                    <div class="space-y-3 mb-4">
                        @foreach($awayStarters as $player)
                        <div class="bg-gray-700 rounded-lg p-3 flex items-center justify-between hover:bg-gray-600 transition">
                            <div class="flex items-center space-x-3">
                                <div class="bg-green-600 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold">
                                    {{ $player->player->jersey_number }}
                                </div>
                                <div>
                                    <div class="font-semibold text-white">{{ $player->player->full_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $player->player->position }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <h5 class="text-md font-semibold mt-4 mb-2 text-gray-300">Remplaçants</h5>
                    <div class="space-y-2">
                        @foreach($awaySubstitutes as $player)
                        <div class="bg-gray-700 rounded-lg p-2 flex items-center justify-between hover:bg-gray-600 transition">
                            <div class="flex items-center space-x-2">
                                <div class="bg-green-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">
                                    {{ $player->player->jersey_number }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-white">{{ $player->player->full_name }}</div>
                                    <div class="text-xs text-gray-400">{{ $player->player->position }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 p-3 bg-gray-800 rounded-lg">
                        <strong>Entraîneur:</strong> {{ $match->away_coach ?? 'Non spécifié' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglet Événements -->
        <div id="events" class="tab-content-sofascore">
            <div class="events-section-sofascore">
                <h3 class="text-xl font-bold mb-4 text-white">Tous les événements</h3>
                
                @if($match->matchEvents->count() > 0)
                    @foreach($match->matchEvents->sortByDesc('minute') as $event)
                        <div class="event-item-sofascore" data-event-id="{{ $event->id }}">
                            <div class="event-minute-sofascore">{{ $event->minute }}'</div>
                            <div class="event-icon-sofascore 
                                @if($event->event_type === 'goal') event-goal
                                @elseif(in_array($event->event_type, ['yellow_card', 'red_card'])) event-card
                                @elseif($event->event_type === 'substitution_in') event-sub
                                @endif
                            ">
                                @if($event->event_type === 'goal') ⚽
                                @elseif($event->event_type === 'yellow_card') ⚠
                                @elseif($event->event_type === 'red_card') ❌
                                @elseif($event->event_type === 'substitution_in') ↔
                                @endif
                            </div>
                            <div class="event-description-sofascore">
                                @if($event->event_type === 'goal')
                                    <span class="player-name-sofascore">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span> 
                                    a marqué
                                    @if($event->assistPlayer)
                                        (assist: {{ $event->assistPlayer->full_name }})
                                    @endif
                                @elseif($event->event_type === 'yellow_card')
                                    <span class="player-name-sofascore">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span> 
                                    a reçu un carton jaune
                                @elseif($event->event_type === 'red_card')
                                    <span class="player-name-sofascore">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span> 
                                    a reçu un carton rouge
                                @elseif($event->event_type === 'substitution_in')
                                    <span class="player-name-sofascore">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span> 
                                    remplace {{ $event->outPlayer->full_name ?? 'Joueur inconnu' }}
                                @endif
                                pour {{ $event->team->university->short_name ?? 'Équipe' }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-gray-400 text-center py-4">Aucun événement enregistré pour ce match.</p>
                @endif
            </div>
        </div>

        <!-- Onglet Statistiques -->
        <div id="stats" class="tab-content-sofascore">
            <div class="match-info-section-sofascore">
                <h3 class="text-xl font-bold mb-6 text-white">Statistiques du match</h3>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Tirs</span>
                            <span class="text-lg">⚽</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-lg home-shots">{{ $match->home_shots ?? 0 }}</span>
                            </div>
                            <span class="text-gray-400 mx-1">|</span>
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-lg away-shots">{{ $match->away_shots ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Tirs cadrés</span>
                            <span class="text-lg">🎯</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg home-shots-on-target">{{ $match->home_shots_on_target ?? 0 }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="font-bold text-lg away-shots-on-target">{{ $match->away_shots_on_target ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Corners</span>
                            <span class="text-lg">🏁</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg home-corners">{{ $match->home_corners ?? 0 }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="font-bold text-lg away-corners">{{ $match->away_corners ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Fautes</span>
                            <span class="text-lg">⚠️</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg home-fouls">{{ $match->home_fouls ?? 0 }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="font-bold text-lg away-fouls">{{ $match->away_fouls ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Hors-jeux</span>
                            <span class="text-lg">🚫</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg home-offsides">{{ $match->home_offsides ?? 0 }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="font-bold text-lg away-offsides">{{ $match->away_offsides ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Cartons jaunes</span>
                            <span class="text-lg">🟨</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg home-yellow-cards">{{ $match->home_yellow_cards ?? 0 }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="font-bold text-lg away-yellow-cards">{{ $match->away_yellow_cards ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Cartons rouges</span>
                            <span class="text-lg">🟥</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg home-red-cards">{{ $match->home_red_cards ?? 0 }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="font-bold text-lg away-red-cards">{{ $match->away_red_cards ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Arrêts</span>
                            <span class="text-lg">🧤</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg home-saves">{{ $match->home_saves ?? 0 }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="font-bold text-lg away-saves">{{ $match->away_saves ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Coups francs</span>
                            <span class="text-lg">🎯</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg home-free-kicks">{{ $match->home_free_kicks ?? 0 }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="font-bold text-lg away-free-kicks">{{ $match->away_free_kicks ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <div class="stat-card-sofascore">
                        <div class="text-xs font-medium text-gray-300 mb-2 flex items-center justify-between">
                            <span>Touches</span>
                            <span class="text-lg">🤲</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg home-throw-ins">{{ $match->home_throw_ins ?? 0 }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="font-bold text-lg away-throw-ins">{{ $match->away_throw_ins ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Onglet Infos -->
        <div id="info" class="tab-content-sofascore">
            <div class="match-info-section-sofascore">
                <h3 class="text-xl font-bold mb-6 text-white">Informations sur le match</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="info-row-sofascore">
                        <span class="info-label-sofascore">Stade</span>
                        <span class="info-value-sofascore">{{ $match->venue }}</span>
                    </div>
                    <div class="info-row-sofascore">
                        <span class="info-label-sofascore">Type de match</span>
                        <span class="info-value-sofascore">{{ ucfirst($match->match_type ?? 'Non spécifié') }}</span>
                    </div>
                    <div class="info-row-sofascore">
                        <span class="info-label-sofascore">Date et heure</span>
                        <span class="info-value-sofascore">{{ $match->match_date->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-row-sofascore">
                        <span class="info-label-sofascore">Tour</span>
                        <span class="info-value-sofascore">{{ $match->round ?? 'Non spécifié' }}</span>
                    </div>
                    <div class="info-row-sofascore">
                        <span class="info-label-sofascore">Groupe</span>
                        <span class="info-value-sofascore">{{ $match->group ?? 'Non spécifié' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Masquer l'animation de chargement après le chargement de la page
    window.addEventListener('load', function() {
        setTimeout(function() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.style.opacity = '0';
                loadingOverlay.style.visibility = 'hidden';
            }
        }, 500);
    });
    
    // Animation pour les événements lors de l'affichage
    function animateEvents() {
        const events = document.querySelectorAll('.event-item-sofascore');
        events.forEach((event, index) => {
            setTimeout(() => {
                event.style.opacity = '1';
                event.style.transform = 'translateX(0)';
            }, index * 100);
        });
    }
    
    function showTab(tabName) {
        // Masquer tous les contenus d'onglets
        const tabContents = document.querySelectorAll('.tab-content-sofascore');
        tabContents.forEach(content => {
            content.classList.remove('active');
        });
        
        // Désactiver tous les onglets
        const tabs = document.querySelectorAll('.tab-sofascore');
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });
        
        // Afficher l'onglet sélectionné
        const selectedTab = document.getElementById(tabName);
        selectedTab.classList.add('active');
        
        // Activer le bouton de l'onglet
        event.currentTarget.classList.add('active');
        
        // Animer le contenu en fonction de l'onglet
        if (tabName === 'events') {
            setTimeout(animateEvents, 100);
        }
    }
    
    // Initialiser les animations après le chargement
    document.addEventListener('DOMContentLoaded', function() {
        animateEvents();
    });
    
    // Fonction pour mettre à jour les statistiques et le score
    function updateMatchData(data) {
        // Mettre à jour le score avec animation
        const scoreElement = document.querySelector('.score-sofascore');
        if (scoreElement && data.match) {
            scoreElement.style.transform = 'scale(1.2)';
            scoreElement.style.color = '#ef4444';
            
            setTimeout(() => {
                scoreElement.textContent = data.match.home_score + ' - ' + data.match.away_score;
                scoreElement.style.transform = 'scale(1)';
                scoreElement.style.color = '';
            }, 300);
        }
        
        // Mettre à jour les statistiques si elles existent
        if (data.statistics) {
            const stats = data.statistics;
            
            // Liste de toutes les statistiques à mettre à jour
            const statMappings = [
                { selector: '.home-shots', stat: 'shots', team: 'home' },
                { selector: '.away-shots', stat: 'shots', team: 'away' },
                { selector: '.home-shots-on-target', stat: 'shots_on_target', team: 'home' },
                { selector: '.away-shots-on-target', stat: 'shots_on_target', team: 'away' },
                { selector: '.home-corners', stat: 'corners', team: 'home' },
                { selector: '.away-corners', stat: 'corners', team: 'away' },
                { selector: '.home-fouls', stat: 'fouls', team: 'home' },
                { selector: '.away-fouls', stat: 'fouls', team: 'away' },
                { selector: '.home-offsides', stat: 'offsides', team: 'home' },
                { selector: '.away-offsides', stat: 'offsides', team: 'away' },
                { selector: '.home-yellow-cards', stat: 'yellow_cards', team: 'home' },
                { selector: '.away-yellow-cards', stat: 'yellow_cards', team: 'away' },
                { selector: '.home-red-cards', stat: 'red_cards', team: 'home' },
                { selector: '.away-red-cards', stat: 'red_cards', team: 'away' },
                { selector: '.home-saves', stat: 'saves', team: 'home' },
                { selector: '.away-saves', stat: 'saves', team: 'away' },
                { selector: '.home-free-kicks', stat: 'free_kicks', team: 'home' },
                { selector: '.away-free-kicks', stat: 'free_kicks', team: 'away' },
                { selector: '.home-throw-ins', stat: 'throw_ins', team: 'home' },
                { selector: '.away-throw-ins', stat: 'throw_ins', team: 'away' }
            ];
            
            statMappings.forEach(mapping => {
                const element = document.querySelector(mapping.selector);
                if (element && stats[mapping.stat] && stats[mapping.stat][mapping.team] !== undefined) {
                    const newValue = stats[mapping.stat][mapping.team];
                    const oldValue = element.textContent;
                    
                    // Vérifier si la valeur a vraiment changé
                    if (newValue !== oldValue) {
                        element.textContent = newValue;
                        // Animation pour la mise à jour
                        element.style.transform = 'scale(1.1)';
                        element.style.color = '#3b82f6';
                        
                        setTimeout(() => {
                            element.style.transform = 'scale(1)';
                            element.style.color = '';
                        }, 300);
                    }
                }
            });
        }
    }
    
    const seenEventIds = new Set();

    // Fonction pour ajouter un nouvel événement
    function addEvent(event) {
        const eventId = event.id || event.event_id;
        if (eventId) {
            if (seenEventIds.has(eventId) || document.querySelector(`[data-event-id="${eventId}"]`)) {
                return;
            }
            seenEventIds.add(eventId);
        }
        const containers = document.querySelectorAll('#events .events-section-sofascore, #overview .events-section-sofascore');
        if (!containers.length) return;

        const playerName = (event.player && event.player.full_name)
            ? event.player.full_name
            : (event.player_name || 'Joueur inconnu');
        const assistName = (event.assist_player && event.assist_player.full_name)
            ? event.assist_player.full_name
            : (event.assist_name || null);
        const outPlayerName = (event.out_player && event.out_player.full_name)
            ? event.out_player.full_name
            : (event.out_player_name || 'Joueur inconnu');

        let eventIcon = '⚽';
        let eventClass = 'event-goal';
        let eventDescription = `
            <span class="player-name-sofascore">${playerName}</span> 
            a marqué
            ${assistName ? ' (assist: ' + assistName + ')' : ''}
        `;
        let notificationMessage = `${playerName} a marqué pour ${event.team_id === {{ $match->home_team_id }} ? '{{ $match->homeTeam->university->short_name }}' : '{{ $match->awayTeam->university->short_name }}'}`;

        switch(event.event_type) {
            case 'goal':
                eventIcon = '⚽';
                eventClass = 'event-goal';
                eventDescription = `
                    <span class="player-name-sofascore">${playerName}</span> 
                    a marqué
                    ${assistName ? ' (assist: ' + assistName + ')' : ''}
                `;
                notificationMessage = `${playerName} a marqué pour ${event.team_id === {{ $match->home_team_id }} ? '{{ $match->homeTeam->university->short_name }}' : '{{ $match->awayTeam->university->short_name }}'}`;
                break;
            case 'yellow_card':
                eventIcon = '⚠';
                eventClass = 'event-card';
                eventDescription = `
                    <span class="player-name-sofascore">${playerName}</span> 
                    a reçu un carton jaune
                `;
                notificationMessage = `${playerName} a reçu un carton jaune pour ${event.team_id === {{ $match->home_team_id }} ? '{{ $match->homeTeam->university->short_name }}' : '{{ $match->awayTeam->university->short_name }}'}`;
                break;
            case 'red_card':
                eventIcon = '❌';
                eventClass = 'event-card';
                eventDescription = `
                    <span class="player-name-sofascore">${playerName}</span> 
                    a reçu un carton rouge
                `;
                notificationMessage = `${playerName} a reçu un carton rouge pour ${event.team_id === {{ $match->home_team_id }} ? '{{ $match->homeTeam->university->short_name }}' : '{{ $match->awayTeam->university->short_name }}'}`;
                break;
            case 'substitution_in':
                eventIcon = '↔';
                eventClass = 'event-sub';
                eventDescription = `
                    <span class="player-name-sofascore">${playerName}</span> 
                    remplace ${outPlayerName}
                `;
                notificationMessage = `${playerName} remplace ${outPlayerName} pour ${event.team_id === {{ $match->home_team_id }} ? '{{ $match->homeTeam->university->short_name }}' : '{{ $match->awayTeam->university->short_name }}'}`;
                break;
        }

        const teamName = event.team_name
            ? event.team_name
            : (event.team_id === {{ $match->home_team_id }}
                ? '{{ $match->homeTeam->university->short_name }}'
                : '{{ $match->awayTeam->university->short_name }}');

        function buildEventElement() {
            const eventElement = document.createElement('div');
            eventElement.className = 'event-item-sofascore new';
            if (eventId) {
                eventElement.dataset.eventId = eventId;
            }
            eventElement.style.opacity = '1';
            eventElement.style.transform = 'translateX(0)';
            eventElement.innerHTML = `
                <div class="event-minute-sofascore">${event.minute}'</div>
                <div class="event-icon-sofascore ${eventClass}">${eventIcon}</div>
                <div class="event-description-sofascore">
                    ${eventDescription}
                    pour ${teamName}
                </div>
            `;
            return eventElement;
        }

        containers.forEach(container => {
            const placeholder = container.querySelector('p');
            if (placeholder) {
                placeholder.remove();
            }
            const eventElement = buildEventElement();
            container.insertBefore(eventElement, container.firstChild);
            eventElement.style.animation = 'newEventPulse 1.5s ease-out';

            if (container.closest('#overview')) {
                const items = container.querySelectorAll('.event-item-sofascore');
                if (items.length > 5) {
                    items[items.length - 1].remove();
                }
            }
        });

        showNotification(notificationMessage, 'success');
    }
    
    // Variables pour suivre les timestamps
    let lastEventTime = '1970-01-01 00:00:00';
    let lastUpdateTime = '1970-01-01 00:00:00';

    const timerState = {
        status: '{{ $match->status }}',
        isPaused: {{ $match->timer_paused_at ? 'true' : 'false' }},
        elapsedSeconds: {{ (int) $match->getElapsedTime() }},
        tickInterval: null,
    };

    function formatSeconds(totalSeconds) {
        const safe = Math.max(0, Math.floor(totalSeconds || 0));
        const minutes = String(Math.floor(safe / 60)).padStart(2, '0');
        const seconds = String(safe % 60).padStart(2, '0');
        return `${minutes}:${seconds}`;
    }

    function renderTimer() {
        const timeEl = document.getElementById('elapsed-time');
        if (timeEl) {
            timeEl.textContent = formatSeconds(timerState.elapsedSeconds);
        }
    }

    function applyTimerState(next) {
        if (!next || typeof next !== 'object') return;

        if (next.status !== undefined && next.status !== null) {
            timerState.status = next.status;
        }

        if (next.is_paused !== undefined) {
            timerState.isPaused = !!next.is_paused;
        } else if (next.status === 'live') {
            timerState.isPaused = false;
        }

        if (next.elapsed_time !== undefined && next.elapsed_time !== null) {
            timerState.elapsedSeconds = Math.max(0, Math.floor(next.elapsed_time));
        }

        if (['halftime', 'finished', 'scheduled'].includes(timerState.status)) {
            timerState.isPaused = true;
        }

        renderTimer();

        const timeEl = document.getElementById('elapsed-time');
        if (timeEl) {
            timeEl.style.display = (timerState.status === 'live' || timerState.status === 'halftime') ? '' : 'none';
        }

        const statusEl = document.querySelector('.match-status-sofascore');
        if (statusEl) {
            if (timerState.status === 'live' && timerState.isPaused) {
                statusEl.textContent = 'PAUSE';
            } else {
                const map = {
                    live: 'EN DIRECT',
                    halftime: 'MI-TEMPS',
                    finished: 'TERMINÉ',
                    scheduled: 'À VENIR'
                };
                statusEl.textContent = map[timerState.status] || String(timerState.status || '').toUpperCase();
            }
        }
    }

    function startLocalTimer() {
        if (timerState.tickInterval) {
            return;
        }

        timerState.tickInterval = setInterval(() => {
            if (timerState.status === 'live' && !timerState.isPaused) {
                timerState.elapsedSeconds += 1;
                renderTimer();
            }
        }, 1000);
    }
    
    // Mise à jour en temps réel (tous statuts)
        console.log('Activation de la synchronisation en temps réel pour le match {{ $match->id }}');

        function updateElapsedTime() {
            fetch(`/api/matches/{{ $match->id }}/timer`)
                .then(response => response.json())
                .then(data => {
                    if (!data || !data.success) return;
                    applyTimerState({
                        status: data.status,
                        is_paused: data.is_paused,
                        elapsed_time: data.elapsed_time,
                    });
                    if (data.label) {
                        const statusEl = document.querySelector('.match-status-sofascore');
                        if (statusEl) {
                            statusEl.textContent = data.label;
                        }
                    }
                })
                .catch(() => {});
        }

        function setStatusLabel(status) {
            const statusEl = document.querySelector('.match-status-sofascore');
            if (!statusEl) return;

            const map = {
                live: 'EN DIRECT',
                halftime: 'MI-TEMPS',
                finished: 'TERMINÉ',
                scheduled: 'À VENIR'
            };

            statusEl.textContent = map[status] || String(status || '').toUpperCase();
        }

        function getCurrentScore() {
            const scoreElement = document.querySelector('.score-sofascore');
            if (!scoreElement) return { home: 0, away: 0 };
            const parts = scoreElement.textContent.split('-').map(part => part.trim());
            return {
                home: parts[0] ?? 0,
                away: parts[1] ?? 0
            };
        }

        function updateScoreFromPayload(homeScore, awayScore) {
            if (homeScore === undefined && awayScore === undefined) {
                return;
            }

            const current = getCurrentScore();
            const nextHome = homeScore !== undefined && homeScore !== null ? homeScore : current.home;
            const nextAway = awayScore !== undefined && awayScore !== null ? awayScore : current.away;
            if (String(nextHome) === String(current.home) && String(nextAway) === String(current.away)) {
                return;
            }
            updateMatchData({
                match: {
                    home_score: nextHome,
                    away_score: nextAway
                }
            });
        }

        function updateStatFromPayload(statName, teamSide, value) {
            if (!statName || !teamSide || value === undefined) return;
            if (statName === 'score') return;

            const statsPayload = {};
            statsPayload[statName] = { [teamSide]: value };
            updateMatchData({ statistics: statsPayload });
        }

        function removeEventById(eventId) {
            if (!eventId) return;
            document.querySelectorAll(`[data-event-id="${eventId}"]`).forEach(el => el.remove());
        }

        function startPolling() {
            function testApiConnectivity() {
                const testUrl = '/api/matches/{{ $match->id }}';
                console.log('Test de connectivité API:', testUrl);

                fetch(testUrl)
                    .then(response => {
                        if (response.ok) {
                            console.log('✅ API accessible - Statut:', response.status);
                        } else {
                            console.error('❌ API inaccessible - Statut:', response.status);
                        }
                    })
                    .catch(error => {
                        console.error('❌ Erreur de connexion API:', error.message);
                    });
            }

            testApiConnectivity();

            function fetchUpdates() {
                const urlsToTry = [
                    `/api/matches/{{ $match->id }}/live-update?last_event_time=${encodeURIComponent(lastEventTime)}&last_update_time=${encodeURIComponent(lastUpdateTime)}`,
                    `/matches/{{ $match->id }}/live-update?last_event_time=${encodeURIComponent(lastEventTime)}&last_update_time=${encodeURIComponent(lastUpdateTime)}`,
                    `{{ url("/api/matches/{$match->id}/live-update") }}?last_event_time=${encodeURIComponent(lastEventTime)}&last_update_time=${encodeURIComponent(lastUpdateTime)}`
                ];

                function tryNextUrl(index = 0) {
                    if (index >= urlsToTry.length) {
                        console.error('❌ Toutes les URLs ont échoué');
                        return;
                    }

                    const url = urlsToTry[index];
                    console.log(`Tentative ${index + 1}/${urlsToTry.length} - URL:`, url);

                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                console.error(`Échec avec l'URL ${index + 1} - Statut:`, response.status);
                                tryNextUrl(index + 1);
                                return;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data) {
                                console.log('✅ Succès avec l'URL:', url);
                                console.log('Réponse API complète:', data);

                                if (data.success) {
                                    if (data.match_updated) {
                                        console.log('Mise à jour détectée - Score:', data.match.home_score + ' - ' + data.match.away_score);
                                        updateMatchData(data);
                                    }

                                    if (data.last_event_time) {
                                        lastEventTime = data.last_event_time;
                                    }
                                    if (data.match && data.match.updated_at) {
                                        lastUpdateTime = data.match.updated_at;
                                    }

                                    if (data.new_events && data.new_events.length > 0) {
                                        console.log('Nouveaux événements:', data.new_events.length);
                                        data.new_events.forEach(event => addEvent(event));
                                    } else if (!data.match_updated) {
                                        console.log('Aucune mise à jour détectée');
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.error(`Erreur avec l'URL ${index + 1}:`, error.message);
                            tryNextUrl(index + 1);
                        });
                }

                tryNextUrl(0);
            }

            const updateInterval = setInterval(fetchUpdates, 2000);
            fetchUpdates();

            showNotification('Mises à jour en temps réel activées (polling)', 'info');

            window.addEventListener('beforeunload', () => {
                clearInterval(updateInterval);
            });
        }

        function startEventPolling() {
            function fetchEventsOnly() {
                const url = `/api/matches/{{ $match->id }}/live-update?last_event_time=${encodeURIComponent(lastEventTime)}&last_update_time=${encodeURIComponent(lastUpdateTime)}`;
                fetch(url)
                    .then(response => {
                        if (!response.ok) return;
                        return response.json();
                    })
                    .then(data => {
                        if (!data || !data.success) return;

                        if (data.last_event_time) {
                            lastEventTime = data.last_event_time;
                        }
                        if (data.match && data.match.updated_at) {
                            lastUpdateTime = data.match.updated_at;
                        }

                        if (data.new_events && data.new_events.length > 0) {
                            data.new_events.forEach(event => addEvent(event));
                        }
                    })
                    .catch(() => {});
            }

            fetchEventsOnly();
            const eventsInterval = setInterval(fetchEventsOnly, 3000);

            window.addEventListener('beforeunload', () => {
                clearInterval(eventsInterval);
            });
        }

        function setupRealtime() {
            if (!window.Echo) {
                console.warn('[Realtime] Echo non initialisé, bascule en polling.');
                startLocalTimer();
                updateElapsedTime();
                const elapsedTimeInterval = setInterval(updateElapsedTime, 15000);
                window.addEventListener('beforeunload', () => {
                    clearInterval(elapsedTimeInterval);
                });
                startPolling();
                return;
            }

            const channel = window.Echo.channel('match.{{ $match->id }}');

            channel
                .listen('MatchEventOccurred', function (payload) {
                    if (payload.action === 'deleted') {
                        removeEventById(payload.event_id);
                    }

                    if (payload.new_event_data || payload.event_type) {
                        addEvent(payload.new_event_data || payload);
                    }

                    if (payload.home_score !== undefined || payload.away_score !== undefined) {
                        updateScoreFromPayload(payload.home_score, payload.away_score);
                    }
                })
                .listen('MatchStatusOrStatsUpdated', function (payload) {
                    if (payload.status) {
                        setStatusLabel(payload.status);
                    }

                    if (payload.stat_name && payload.team_side) {
                        const value = payload[`${payload.team_side}_${payload.stat_name}`];
                        if (payload.stat_name === 'score') {
                            updateScoreFromPayload(payload.home_score, payload.away_score);
                        } else {
                            updateStatFromPayload(payload.stat_name, payload.team_side, value);
                        }
                    }

                    if (payload.home_score !== undefined || payload.away_score !== undefined) {
                        updateScoreFromPayload(payload.home_score, payload.away_score);
                    }

                    if (payload.elapsed_time !== undefined || payload.timer_paused_at !== undefined || payload.status) {
                        applyTimerState({
                            status: payload.status,
                            is_paused: payload.timer_paused_at !== undefined ? !!payload.timer_paused_at : undefined,
                            elapsed_time: payload.elapsed_time,
                        });
                    }
                });

            channel.listenToAll(function (eventName, payload) {
                if (!payload) return;
                if (payload.new_event_data || payload.event_type) {
                    addEvent(payload.new_event_data || payload);
                }
            });

            showNotification('Mises à jour en temps réel activées', 'success');
        }

        startLocalTimer();
        updateElapsedTime();
        setupRealtime();
        startEventPolling();
        const elapsedTimeInterval = setInterval(updateElapsedTime, 15000);
        window.addEventListener('beforeunload', () => {
            clearInterval(elapsedTimeInterval);
        });
</script>
@endsection
