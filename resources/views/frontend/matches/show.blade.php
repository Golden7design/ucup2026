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
    
    .stat-card-sofascore {
        background: #1f2937;
        border-radius: 12px;
        padding: 18px 15px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        animation: statCardAppear 0.5s ease-out backwards;
    }
    
    @keyframes statCardAppear {
        0% { opacity: 0; transform: translateY(20px) scale(0.8); }
        80% { opacity: 1; transform: translateY(-5px) scale(1.05); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    
    .stat-card-sofascore::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .stat-card-sofascore:hover::before {
        transform: scaleX(1);
    }
    
    .stat-card-sofascore:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
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

    .stats-panel-sofascore {
        background: linear-gradient(180deg, #0f172a 0%, #0b1220 100%);
        border: 1px solid #1f2937;
        border-radius: 18px;
        padding: 20px 16px;
        box-shadow: inset 0 1px 0 rgba(148, 163, 184, 0.08), 0 20px 30px rgba(2, 6, 23, 0.35);
    }

    .stats-panel-title-sofascore {
        color: #f3f4f6;
        font-size: 1.2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 4px;
    }

    .stats-panel-subtitle-sofascore {
        color: #94a3b8;
        text-align: center;
        font-size: 0.85rem;
        margin-bottom: 16px;
    }

    .stats-list-sofascore {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .stat-row-sofascore {
        background: rgba(17, 24, 39, 0.7);
        border: 1px solid rgba(55, 65, 81, 0.7);
        border-radius: 14px;
        padding: 10px 12px;
        transition: border-color 0.25s ease, background 0.25s ease;
    }

    .stat-row-sofascore.is-updated {
        border-color: rgba(56, 189, 248, 0.8);
        background: rgba(15, 23, 42, 0.95);
    }

    .stat-row-head-sofascore {
        display: grid;
        grid-template-columns: 52px 1fr 52px;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .stat-label-sofascore {
        color: #e5e7eb;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .stat-value-home-sofascore,
    .stat-value-away-sofascore {
        font-weight: 700;
        font-size: 0.85rem;
        text-align: center;
        border-radius: 999px;
        padding: 2px 6px;
        font-variant-numeric: tabular-nums;
    }

    .stat-value-home-sofascore {
        color: #a7f3d0;
        background: rgba(16, 185, 129, 0.18);
    }

    .stat-value-away-sofascore {
        color: #c4b5fd;
        background: rgba(129, 140, 248, 0.18);
    }

    .stat-bar-track-sofascore {
        height: 9px;
        width: 100%;
        background: #111827;
        border-radius: 999px;
        display: flex;
        overflow: hidden;
    }

    .stat-bar-home-sofascore,
    .stat-bar-away-sofascore {
        display: block;
        height: 100%;
        transition: width 0.35s ease;
    }

    .stat-bar-home-sofascore {
        background: linear-gradient(90deg, #16a34a, #4ade80);
    }

    .stat-bar-away-sofascore {
        background: linear-gradient(90deg, #818cf8, #a78bfa);
    }

    .stat-row-possession-sofascore {
        padding-top: 12px;
        padding-bottom: 12px;
    }

    .stat-row-possession-sofascore .stat-label-sofascore {
        font-weight: 700;
    }

    .stat-row-possession-sofascore .stat-bar-track-sofascore {
        height: 16px;
    }

    .stat-bars-dual-sofascore {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .stat-bar-side-track-sofascore {
        height: 6px;
        border-radius: 999px;
        background: #111827;
        overflow: hidden;
    }

    .stat-bar-side-fill-sofascore {
        display: block;
        height: 100%;
        transition: width 0.35s ease;
    }

    .stat-bar-side-fill-home-sofascore {
        background: linear-gradient(90deg, #16a34a, #4ade80);
    }

    .stat-bar-side-fill-away-sofascore {
        background: linear-gradient(90deg, #818cf8, #a78bfa);
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

        .stat-row-head-sofascore {
            grid-template-columns: 46px 1fr 46px;
            gap: 6px;
        }

        .stat-label-sofascore {
            font-size: 0.82rem;
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

    /* Animations pour les notifications */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #3b82f6;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        animation: slideInRight 0.5s ease-out, fadeOut 0.5s ease-out 3s forwards;
        display: none;
    }

    .notification.show {
        display: block;
    }

    .notification.success {
        background: #10b981;
    }

    .notification.error {
        background: #ef4444;
    }

    .notification.info {
        background: #3b82f6;
    }

    /* Animations pour les événements */
    .event-item-sofascore.new {
        animation: newEventPulse 1.5s ease-out;
        background: linear-gradient(90deg, rgba(76, 175, 80, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
    }

    @keyframes newEventPulse {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(10px); }
    }

    /* Animation de mise à jour des statistiques */
    .stat-row-sofascore.new {
        animation: statPulse 1.5s ease-out;
    }

    @keyframes statPulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
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
                        {{ $elapsedTime }}
                    </div>
                    @if(in_array($match->status, ['live', 'halftime']))
                        @if($match->getAdditionalTimeFirstHalf() > 0 || $match->getAdditionalTimeSecondHalf() > 0)
                            <div class="text-sm mt-1 font-bold text-blue-300">
                                +{{ $match->getAdditionalTimeFirstHalf() + $match->getAdditionalTimeSecondHalf() }}'
                            </div>
                        @endif
                        @if($match->isExtraTime())
                            <div class="text-sm mt-1 font-bold text-yellow-300">
                                Prolongations
                            </div>
                        @endif
                        @if($match->isPenaltyShootout())
                            <div class="text-sm mt-1 font-bold text-red-300">
                                Tirs au but
                            </div>
                        @endif
                    @endif
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

        <!-- Onglet Statistiques -->
        <div id="stats" class="tab-content-sofascore">
            <div class="match-info-section-sofascore">
                <h3 class="text-xl font-bold mb-6 text-white">Statistiques du match</h3>

                <div class="stats-panel-sofascore">
                    <div class="stats-panel-title-sofascore">Aperçu du match</div>
                    <div class="stats-panel-subtitle-sofascore">Comparaison domicile vs extérieur</div>

                    @php
                        $statRows = [
                            [
                                'label' => 'Possession de balle',
                                'home' => (int) ($match->home_possession ?? 50),
                                'away' => (int) ($match->away_possession ?? 50),
                                'homeClass' => 'home-possession',
                                'awayClass' => 'away-possession',
                                'format' => 'percent',
                                'suffix' => '%',
                            ],
                            [
                                'label' => 'Total des tirs',
                                'home' => (int) ($match->home_shots ?? 0),
                                'away' => (int) ($match->away_shots ?? 0),
                                'homeClass' => 'home-shots',
                                'awayClass' => 'away-shots',
                                'format' => 'ratio',
                            ],
                            [
                                'label' => 'Tirs cadrés',
                                'home' => (int) ($match->home_shots_on_target ?? 0),
                                'away' => (int) ($match->away_shots_on_target ?? 0),
                                'homeClass' => 'home-shots-on-target',
                                'awayClass' => 'away-shots-on-target',
                                'format' => 'ratio',
                            ],
                            [
                                'label' => 'Arrêts du gardien',
                                'home' => (int) ($match->home_saves ?? 0),
                                'away' => (int) ($match->away_saves ?? 0),
                                'homeClass' => 'home-saves',
                                'awayClass' => 'away-saves',
                                'format' => 'ratio',
                            ],
                            [
                                'label' => 'Corners',
                                'home' => (int) ($match->home_corners ?? 0),
                                'away' => (int) ($match->away_corners ?? 0),
                                'homeClass' => 'home-corners',
                                'awayClass' => 'away-corners',
                                'format' => 'ratio',
                            ],
                            [
                                'label' => 'Fautes',
                                'home' => (int) ($match->home_fouls ?? 0),
                                'away' => (int) ($match->away_fouls ?? 0),
                                'homeClass' => 'home-fouls',
                                'awayClass' => 'away-fouls',
                                'format' => 'ratio',
                            ],
                            [
                                'label' => 'Hors-jeux',
                                'home' => (int) ($match->home_offsides ?? 0),
                                'away' => (int) ($match->away_offsides ?? 0),
                                'homeClass' => 'home-offsides',
                                'awayClass' => 'away-offsides',
                                'format' => 'ratio',
                            ],
                            [
                                'label' => 'Coups francs',
                                'home' => (int) ($match->home_free_kicks ?? 0),
                                'away' => (int) ($match->away_free_kicks ?? 0),
                                'homeClass' => 'home-free-kicks',
                                'awayClass' => 'away-free-kicks',
                                'format' => 'ratio',
                            ],
                            [
                                'label' => 'Touches',
                                'home' => (int) ($match->home_throw_ins ?? 0),
                                'away' => (int) ($match->away_throw_ins ?? 0),
                                'homeClass' => 'home-throw-ins',
                                'awayClass' => 'away-throw-ins',
                                'format' => 'ratio',
                            ],
                            [
                                'label' => 'Cartons jaunes',
                                'home' => (int) ($match->home_yellow_cards ?? 0),
                                'away' => (int) ($match->away_yellow_cards ?? 0),
                                'homeClass' => 'home-yellow-cards',
                                'awayClass' => 'away-yellow-cards',
                                'format' => 'ratio',
                            ],
                            [
                                'label' => 'Cartons rouges',
                                'home' => (int) ($match->home_red_cards ?? 0),
                                'away' => (int) ($match->away_red_cards ?? 0),
                                'homeClass' => 'home-red-cards',
                                'awayClass' => 'away-red-cards',
                                'format' => 'ratio',
                            ],
                        ];
                    @endphp

                    <div class="stats-list-sofascore">
                        @foreach($statRows as $statRow)
                            @php
                                $homeValue = (float) $statRow['home'];
                                $awayValue = (float) $statRow['away'];
                                $totalValue = $homeValue + $awayValue;
                                $homeWidth = $totalValue > 0 ? ($homeValue / $totalValue) * 100 : 0;
                                $awayWidth = $totalValue > 0 ? ($awayValue / $totalValue) * 100 : 0;
                                $suffix = $statRow['suffix'] ?? '';
                            @endphp
                            <div class="stat-row-sofascore {{ $statRow['format'] === 'percent' ? 'stat-row-possession-sofascore' : '' }}" data-stat-format="{{ $statRow['format'] }}">
                                <div class="stat-row-head-sofascore">
                                    <span class="stat-value-home-sofascore {{ $statRow['homeClass'] }}" data-stat-role="home">{{ (int) $homeValue }}{{ $suffix }}</span>
                                    <span class="stat-label-sofascore">{{ $statRow['label'] }}</span>
                                    <span class="stat-value-away-sofascore {{ $statRow['awayClass'] }}" data-stat-role="away">{{ (int) $awayValue }}{{ $suffix }}</span>
                                </div>
                                @if($statRow['format'] === 'percent')
                                    <div class="stat-bar-track-sofascore">
                                        <span class="stat-bar-home-sofascore" data-stat-bar="home" style="width: {{ number_format($homeWidth, 2, '.', '') }}%"></span>
                                        <span class="stat-bar-away-sofascore" data-stat-bar="away" style="width: {{ number_format($awayWidth, 2, '.', '') }}%"></span>
                                    </div>
                                @else
                                    @php
                                        $maxValue = max($homeValue, $awayValue, 1);
                                        $homeGaugeWidth = ($homeValue / $maxValue) * 100;
                                        $awayGaugeWidth = ($awayValue / $maxValue) * 100;
                                    @endphp
                                    <div class="stat-bars-dual-sofascore">
                                        <div class="stat-bar-side-track-sofascore">
                                            <span class="stat-bar-side-fill-sofascore stat-bar-side-fill-home-sofascore" data-stat-gauge="home" style="width: {{ number_format($homeGaugeWidth, 2, '.', '') }}%"></span>
                                        </div>
                                        <div class="stat-bar-side-track-sofascore">
                                            <span class="stat-bar-side-fill-sofascore stat-bar-side-fill-away-sofascore" data-stat-gauge="away" style="width: {{ number_format($awayGaugeWidth, 2, '.', '') }}%"></span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
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

        const DEBUG = false;
        function debugLog(...args) {
            if (DEBUG) {
                debugLog(...args);
            }
        }
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

    // Fonction pour afficher une notification
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3000);
    }
    
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

    function parseStatValue(rawValue) {
        const parsed = parseFloat(String(rawValue).replace(',', '.').replace(/[^\d.-]/g, ''));
        return Number.isFinite(parsed) ? parsed : 0;
    }

    function refreshStatsBars() {
        const rows = document.querySelectorAll('.stat-row-sofascore');

        rows.forEach((row) => {
            const homeValueEl = row.querySelector('[data-stat-role="home"]');
            const awayValueEl = row.querySelector('[data-stat-role="away"]');
            if (!homeValueEl || !awayValueEl) {
                return;
            }

            const homeValue = parseStatValue(homeValueEl.textContent);
            const awayValue = parseStatValue(awayValueEl.textContent);
            const format = row.dataset.statFormat;

            if (format === 'percent') {
                const homeBar = row.querySelector('[data-stat-bar="home"]');
                const awayBar = row.querySelector('[data-stat-bar="away"]');
                if (!homeBar || !awayBar) {
                    return;
                }

                const totalValue = homeValue + awayValue;
                const homeWidth = totalValue > 0 ? (homeValue / totalValue) * 100 : 0;
                const awayWidth = totalValue > 0 ? (awayValue / totalValue) * 100 : 0;

                homeBar.style.width = `${homeWidth}%`;
                awayBar.style.width = `${awayWidth}%`;
                return;
            }

            const homeGauge = row.querySelector('[data-stat-gauge="home"]');
            const awayGauge = row.querySelector('[data-stat-gauge="away"]');
            if (!homeGauge || !awayGauge) {
                return;
            }

            const maxValue = Math.max(homeValue, awayValue, 1);
            const homeWidth = (homeValue / maxValue) * 100;
            const awayWidth = (awayValue / maxValue) * 100;

            homeGauge.style.width = `${homeWidth}%`;
            awayGauge.style.width = `${awayWidth}%`;
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
        refreshStatsBars();
        
        // Afficher une notification de bienvenue
        showNotification('Bienvenue sur le suivi du match en direct !', 'info');
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
            
            // Afficher une notification pour le score
            showNotification(`Score mis à jour : ${data.match.home_score} - ${data.match.away_score}`, 'success');
        }
        
        // Mettre à jour les statistiques si elles existent
        if (data.statistics) {
            const stats = data.statistics;
            
            // Liste de toutes les statistiques à mettre à jour
            const statMappings = [
                { selector: '.home-possession', stat: 'possession', team: 'home', name: 'Possession', suffix: '%' },
                { selector: '.away-possession', stat: 'possession', team: 'away', name: 'Possession', suffix: '%' },
                { selector: '.home-shots', stat: 'shots', team: 'home', name: 'Tirs' },
                { selector: '.away-shots', stat: 'shots', team: 'away', name: 'Tirs' },
                { selector: '.home-shots-on-target', stat: 'shots_on_target', team: 'home', name: 'Tirs cadrés' },
                { selector: '.away-shots-on-target', stat: 'shots_on_target', team: 'away', name: 'Tirs cadrés' },
                { selector: '.home-corners', stat: 'corners', team: 'home', name: 'Corners' },
                { selector: '.away-corners', stat: 'corners', team: 'away', name: 'Corners' },
                { selector: '.home-fouls', stat: 'fouls', team: 'home', name: 'Fautes' },
                { selector: '.away-fouls', stat: 'fouls', team: 'away', name: 'Fautes' },
                { selector: '.home-offsides', stat: 'offsides', team: 'home', name: 'Hors-jeux' },
                { selector: '.away-offsides', stat: 'offsides', team: 'away', name: 'Hors-jeux' },
                { selector: '.home-yellow-cards', stat: 'yellow_cards', team: 'home', name: 'Cartons jaunes' },
                { selector: '.away-yellow-cards', stat: 'yellow_cards', team: 'away', name: 'Cartons jaunes' },
                { selector: '.home-red-cards', stat: 'red_cards', team: 'home', name: 'Cartons rouges' },
                { selector: '.away-red-cards', stat: 'red_cards', team: 'away', name: 'Cartons rouges' },
                { selector: '.home-saves', stat: 'saves', team: 'home', name: 'Arrêts' },
                { selector: '.away-saves', stat: 'saves', team: 'away', name: 'Arrêts' },
                { selector: '.home-free-kicks', stat: 'free_kicks', team: 'home', name: 'Coups francs' },
                { selector: '.away-free-kicks', stat: 'free_kicks', team: 'away', name: 'Coups francs' },
                { selector: '.home-throw-ins', stat: 'throw_ins', team: 'home', name: 'Touches' },
                { selector: '.away-throw-ins', stat: 'throw_ins', team: 'away', name: 'Touches' }
            ];
            
            statMappings.forEach(mapping => {
                const element = document.querySelector(mapping.selector);
                if (element && stats[mapping.stat] && stats[mapping.stat][mapping.team] !== undefined) {
                    const newValue = stats[mapping.stat][mapping.team];
                    const newDisplayValue = `${newValue}${mapping.suffix || ''}`;
                    const oldValue = element.textContent.trim();
                    
                    // Vérifier si la valeur a vraiment changé
                    if (newDisplayValue !== oldValue) {
                        element.textContent = newDisplayValue;
                        // Animation pour la mise à jour
                        element.style.transform = 'scale(1.1)';
                        element.style.color = '#3b82f6';

                        const row = element.closest('.stat-row-sofascore');
                        if (row) {
                            row.classList.add('is-updated');
                            row.classList.add('new');
                            setTimeout(() => {
                                row.classList.remove('is-updated');
                                row.classList.remove('new');
                            }, 600);
                        }
                        
                        setTimeout(() => {
                            element.style.transform = 'scale(1)';
                            element.style.color = '';
                        }, 300);
                        
                        // Afficher une notification pour la statistique
                        const teamName = mapping.team === 'home' ? '{{ $match->homeTeam->university->short_name }}' : '{{ $match->awayTeam->university->short_name }}';
                        showNotification(`${mapping.name} mis à jour : ${teamName} ${newValue}`, 'info');
                    }
                }
            });

            refreshStatsBars();
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

        // Afficher une notification pour l'événement
        showNotification(notificationMessage, 'success');
    }
    
    // Variables pour suivre les timestamps
    let lastEventTime = '1970-01-01 00:00:00';
    let lastUpdateTime = '1970-01-01 00:00:00';
    
    // Timer state - données reçues du backend uniquement
    const timerState = {
        status: '{{ $match->status }}',
        isPaused: {{ $match->timer_paused_at ? 'true' : 'false' }},
        elapsedSeconds: {{ (int) $match->getElapsedTime() }},
        lastUpdate: Date.now(), // Timestamp de la dernière mise à jour reçue
        tickInterval: null,
    };

    function formatSeconds(totalSeconds) {
        const safe = Math.max(0, Math.floor(totalSeconds || 0));
        const minutes = String(Math.floor(safe / 60)).padStart(2, '0');
        const seconds = String(safe % 60).padStart(2, '0');
        return `${minutes}:${seconds}`;
    }

    // Rend le timer - données purement backend-driven
    function renderTimer() {
        const timeEl = document.getElementById('elapsed-time');
        if (timeEl) {
            timeEl.textContent = formatSeconds(timerState.elapsedSeconds);
        }
    }

    // Applique les mises à jour reçues du backend (WebSocket ou API)
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
            timerState.lastUpdate = Date.now();
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

    // Fonction pour mettre à jour le temps écoulé
    function updateElapsedTime() {
        fetch(`/api/matches/{{ $match->id }}/timer`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
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
                }
            });
    }

    // Mise à jour en temps réel (tous statuts)
        debugLog('Activation de la synchronisation en temps réel pour le match {{ $match->id }}');

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
            document.querySelectorAll(`[data-event-id=\"${eventId}\"]`).forEach(el => el.remove());
        }

        function startPolling() {
            // Fonction pour tester la connectivité API
            function testApiConnectivity() {
                const testUrl = '/api/matches/{{ $match->id }}';
                debugLog('Test de connectivité API:', testUrl);

                fetch(testUrl)
                    .then(response => {
                        if (response.ok) {
                            debugLog('✅ API accessible - Statut:', response.status);
                        } else {
                            console.error('❌ API inaccessible - Statut:', response.status);
                        }
                    })
                    .catch(error => {
                        console.error('❌ Erreur de connexion API:', error.message);
                    });
            }

            // Tester la connectivité immédiatement
            testApiConnectivity();

            // Fonction pour récupérer les mises à jour
            function fetchUpdates() {
                // Essayer différentes URLs pour résoudre le problème 404
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
                    debugLog(`Tentative ${index + 1}/${urlsToTry.length} - URL:`, url);

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
                                debugLog('✅ Succès avec l\'URL:', url);
                                debugLog('Réponse API complète:', data);

                                if (data.success) {
                                    if (data.match_updated) {
                                        debugLog('Mise à jour détectée - Score:', data.match.home_score + ' - ' + data.match.away_score);
                                        updateMatchData(data);
                                    }

                                    // Mettre à jour les timestamps
                                    if (data.last_event_time) {
                                        lastEventTime = data.last_event_time;
                                    }
                                    if (data.match && data.match.updated_at) {
                                        lastUpdateTime = data.match.updated_at;
                                    }

                                    // Ajouter les nouveaux événements même si match_updated=false
                                    if (data.new_events && data.new_events.length > 0) {
                                        debugLog('Nouveaux événements:', data.new_events.length);
                                        data.new_events.forEach(event => addEvent(event));
                                    } else if (!data.match_updated) {
                                        debugLog('Aucune mise à jour détectée');
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

            // Polling pour événements (toutes les 2 secondes)
            const updateInterval = setInterval(fetchUpdates, 2000);

            // Polling pour timer (fallback toutes les 15 secondes - pas de timer local JavaScript)
            const elapsedTimeInterval = setInterval(updateElapsedTime, 5000);

            // Première mise à jour immédiate
            fetchUpdates();

            // Afficher une notification
            showNotification('Mises à jour en temps réel activées (polling)', 'info');

            // Nettoyage lors du changement de page
            window.addEventListener('beforeunload', () => {
                clearInterval(updateInterval);
                clearInterval(elapsedTimeInterval);
            });
        }

        // Configuration du temps réel (WebSocket + Polling Fallback)
        function setupRealtime() {
            // Si Echo n'est pas dispo ou n'a pas de méthode channel, utiliser polling
            if (!window.Echo || typeof window.Echo.channel !== 'function') {
                console.warn('[Realtime] Echo non dispo, polling actif');
                startPolling();
                return;
            }

            // WebSocket disponible
            try {
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
                        debugLog('[Timer] WebSocket received:', payload);
                        
                        // Timer tick
                        if (payload.type === 'timer_tick' && payload.elapsed_time !== undefined) {
                            debugLog('[Timer] Tick:', payload.elapsed_time);
                            timerState.elapsedSeconds = Math.max(0, Math.floor(payload.elapsed_time));
                            renderTimer();
                            return;
                        }

                        if (payload.status) {
                            debugLog('[Timer] Status:', payload.status);
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

                        debugLog('[Timer] elapsed:', payload.elapsed_time, 'current:', timerState.elapsedSeconds);
                        if (payload.elapsed_time !== undefined || payload.timer_paused_at !== undefined || payload.status) {
                            applyTimerState({
                                status: payload.status,
                                is_paused: payload.timer_paused_at !== undefined ? !!payload.timer_paused_at : undefined,
                                elapsed_time: payload.elapsed_time,
                            });
                        }
                    });
                
                debugLog('✅ WebSocket actif');
            } catch (e) {
                console.error('[Realtime] Erreur WebSocket:', e);
                // Fallback vers polling
                startPolling();
            }
        }

        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            renderTimer(); // Affiche le timer initial (backend-driven)
            startLocalTimer();
            updateElapsedTime();
            setupRealtime();
            
            // DEBUG: Vérifier l'état du timer
            debugLog('=== TIMER DEBUG ===');
            debugLog('Status:', timerState.status);
            debugLog('IsPaused:', timerState.isPaused);
            debugLog('ElapsedSeconds:', timerState.elapsedSeconds);
            debugLog('Formatted:', formatSeconds(timerState.elapsedSeconds));
        });
</script>
@endsection
