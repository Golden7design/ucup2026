@extends('layouts.app')
@section('title', 'Matchs en Direct - U-Cup')

@section('content')
<style>
    .match-live-container {
        background: #1a1a2e;
        min-height: 100vh;
        color: white;
        padding: 20px;
    }
    .match-card {
        background-color: #16213e;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 20px;
    }
    .team-logo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: contain;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }
    .live-indicator {
        background: linear-gradient(90deg, #e94560, #ff6b6b);
        animation: pulse 1.5s infinite;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: bold;
        display: inline-flex;
        align-items: center;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(233, 69, 96, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(233, 69, 96, 0); }
        100% { box-shadow: 0 0 0 0 rgba(233, 69, 96, 0); }
    }
    .score-display {
        font-family: 'Arial Rounded MT Bold', 'Arial', sans-serif;
        font-size: 2.5rem;
        font-weight: bold;
        background: rgba(0, 0, 0, 0.3);
        padding: 8px 16px;
        border-radius: 8px;
        margin: 10px 0;
    }
    .time-counter {
        font-family: monospace;
        font-size: 1rem;
        font-weight: bold;
        color: #ef4444;
        margin-left: 10px;
    }
    .stat-box {
        background: #2d3748;
        border-radius: 8px;
        padding: 8px;
        text-align: center;
        margin: 5px 0;
    }
    .event-item {
        border-left: 3px solid #e94560;
        padding-left: 12px;
        margin-bottom: 8px;
    }
    .event-item.new {
        background-color: rgba(233, 69, 96, 0.2);
        animation: highlight 2s;
    }
    @keyframes highlight {
        0% { background-color: rgba(233, 69, 96, 0.4); }
        100% { background-color: transparent; }
    }
</style>

<div class="match-live-container">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 flex items-center">
            <span class="relative flex h-6 w-6 mr-4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-6 w-6 bg-red-500"></span>
            </span>
            Matchs en Direct
        </h1>

        @if(isset($liveMatches) && $liveMatches->count() > 0)
            @foreach($liveMatches as $match)
            <div class="match-card p-6" id="match-{{ $match->id }}">
                <div class="flex justify-between items-center mb-4">
                    <div class="live-indicator">
                        @if($match->status === 'live')
                            EN DIRECT
                            @if($match->start_time)
                                <span class="time-counter" id="match-time-{{ $match->id }}">
                                    {{ now()->diffInMinutes($match->start_time) }}'
                                </span>
                            @endif
                        @elseif($match->status === 'halftime')
                            MI-TEMPS
                        @else
                            {{ strtoupper($match->status) }}
                        @endif
                    </div>
                    <div class="text-gray-400 text-sm">
                        {{ $match->match_date->format('d/m/Y H:i') }} • {{ $match->venue ?? 'Stade inconnu' }}
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <div class="text-center flex-1">
                        @if($match->homeTeam->university->logo ?? false)
                            <img src="{{ asset('storage/' . $match->homeTeam->university->logo) }}" alt="{{ $match->homeTeam->university->name ?? 'Équipe' }}" class="team-logo mx-auto mb-2">
                        @else
                            <div class="team-logo bg-gray-700 rounded-full mx-auto mb-2 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                        @endif
                        <h2 class="text-lg font-bold">{{ $match->homeTeam->university->short_name ?? 'Équipe' }}</h2>
                    </div>

                    <div class="score-display">
                        <span id="home-score-{{ $match->id }}">{{ $match->home_score ?? 0 }}</span> -
                        <span id="away-score-{{ $match->id }}">{{ $match->away_score ?? 0 }}</span>
                    </div>

                    <div class="text-center flex-1">
                        @if($match->awayTeam->university->logo ?? false)
                            <img src="{{ asset('storage/' . $match->awayTeam->university->logo) }}" alt="{{ $match->awayTeam->university->name ?? 'Équipe' }}" class="team-logo mx-auto mb-2">
                        @else
                            <div class="team-logo bg-gray-700 rounded-full mx-auto mb-2 flex items-center justify-center">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                        @endif
                        <h2 class="text-lg font-bold">{{ $match->awayTeam->university->short_name ?? 'Équipe' }}</h2>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <div class="stat-box">
                        <div class="text-gray-400 text-xs">Fautes</div>
                        <div class="text-sm font-bold">
                            <span id="home-fouls-{{ $match->id }}">{{ $match->home_fouls ?? 0 }}</span> -
                            <span id="away-fouls-{{ $match->id }}">{{ $match->away_fouls ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="text-gray-400 text-xs">Corners</div>
                        <div class="text-sm font-bold">
                            <span id="home-corners-{{ $match->id }}">{{ $match->home_corners ?? 0 }}</span> -
                            <span id="away-corners-{{ $match->id }}">{{ $match->away_corners ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="text-gray-400 text-xs">Cartons</div>
                        <div class="text-sm font-bold">
                            {{ ($match->home_yellow_cards ?? 0) + ($match->home_red_cards ?? 0) }} -
                            {{ ($match->away_yellow_cards ?? 0) + ($match->away_red_cards ?? 0) }}
                        </div>
                    </div>
                </div>

                <!-- Événements récents -->
                <div class="mt-4">
                    <h3 class="text-sm font-bold mb-2">Derniers événements</h3>
                    <div class="space-y-2" id="match-events-{{ $match->id }}">
                        @if($match->matchEvents && $match->matchEvents->count() > 0)
                            @foreach($match->matchEvents->sortByDesc('minute')->take(3) as $event)
                            <div class="event-item p-2 rounded-lg bg-gray-800" data-event-id="{{ $event->id }}">
                                <div class="flex items-center">
                                    <div class="mr-2">
                                        <span class="text-gray-400 text-xs">{{ $event->minute }}'</span>
                                    </div>
                                    <div class="mr-2">
                                        @if($event->player && $event->player->photo_url)
                                            <img src="{{ $event->player->photo_url }}" alt="{{ $event->player->full_name ?? 'Joueur' }}" class="w-6 h-6 rounded-full object-cover">
                                        @else
                                            <div class="w-6 h-6 rounded-full bg-gray-600 flex items-center justify-center">
                                                <i class="fas fa-user text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center">
                                            <div class="font-bold text-sm">
                                                @if($event->event_type == 'goal')
                                                    <span class="text-green-400">⚽ BUT!</span>
                                                @elseif($event->event_type == 'yellow_card')
                                                    <span class="text-yellow-400">🟨 Carton jaune</span>
                                                @elseif($event->event_type == 'red_card')
                                                    <span class="text-red-400">🟥 Carton rouge</span>
                                                @elseif($event->event_type == 'substitution_in')
                                                    <span class="text-blue-400">🔄 Remplacement</span>
                                                @endif
                                                <span class="ml-1">{{ $event->player->full_name ?? 'Joueur inconnu' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <p class="text-gray-400 text-center py-2 text-sm">Aucun événement récent</p>
                        @endif
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('matches.show', $match) }}" class="text-blue-400 hover:underline text-sm">
                            Voir tous les détails
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="bg-gray-800 rounded-lg p-8 text-center">
                <div class="mb-4">
                    <i class="fas fa-broadcast-tower text-4xl text-gray-400"></i>
                </div>
                <h2 class="text-xl font-bold text-gray-300 mb-2">Aucun match en direct</h2>
                <p class="text-gray-400 mb-6">Revenez plus tard pour suivre les rencontres en temps réel.</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($liveMatches as $match)
    @if($match->status === 'live')
    // Mettre à jour le temps de jeu pour le match {{ $match->id }}
    function updateMatchTime{{ $match->id }}() {
        const startTime = new Date('{{ $match->start_time }}');
        const now = new Date();
        const diffMinutes = Math.floor((now - startTime) / 60000);

        const timeElement = document.getElementById('match-time-{{ $match->id }}');
        if (timeElement) {
            timeElement.textContent = diffMinutes + "'";
        }
    }

    // Mettre à jour immédiatement
    updateMatchTime{{ $match->id }}();

    // Mettre à jour chaque minute
    setInterval(updateMatchTime{{ $match->id }}, 60000);

    function applyScores{{ $match->id }}(homeScore, awayScore) {
        if (homeScore !== undefined) {
            const homeEl = document.getElementById('home-score-{{ $match->id }}');
            if (homeEl) homeEl.textContent = homeScore;
        }
        if (awayScore !== undefined) {
            const awayEl = document.getElementById('away-score-{{ $match->id }}');
            if (awayEl) awayEl.textContent = awayScore;
        }
    }

    function applyStats{{ $match->id }}(payload) {
        if (payload.home_fouls !== undefined) {
            const el = document.getElementById('home-fouls-{{ $match->id }}');
            if (el) el.textContent = payload.home_fouls;
        }
        if (payload.away_fouls !== undefined) {
            const el = document.getElementById('away-fouls-{{ $match->id }}');
            if (el) el.textContent = payload.away_fouls;
        }
        if (payload.home_corners !== undefined) {
            const el = document.getElementById('home-corners-{{ $match->id }}');
            if (el) el.textContent = payload.home_corners;
        }
        if (payload.away_corners !== undefined) {
            const el = document.getElementById('away-corners-{{ $match->id }}');
            if (el) el.textContent = payload.away_corners;
        }
    }

    function removeEventById{{ $match->id }}(eventId) {
        if (!eventId) return;
        document.querySelectorAll(`#match-events-{{ $match->id }} [data-event-id="${eventId}"]`).forEach(el => el.remove());
    }

    const seenEventIds{{ $match->id }} = new Set();

    function addRealtimeEvent{{ $match->id }}(event) {
        const eventsContainer = document.getElementById('match-events-{{ $match->id }}');
        if (!eventsContainer) return;

        const placeholder = eventsContainer.querySelector('p');
        if (placeholder) {
            placeholder.remove();
        }

        const eventElement = document.createElement('div');
        eventElement.className = 'event-item new p-2 rounded-lg bg-gray-800';
        const eventId = event.id || event.event_id;
        if (eventId) {
            if (seenEventIds{{ $match->id }}.has(eventId) || eventsContainer.querySelector(`[data-event-id="${eventId}"]`)) {
                return;
            }
            seenEventIds{{ $match->id }}.add(eventId);
            eventElement.dataset.eventId = eventId;
        }

        let eventIcon = '';
        if (event.event_type === 'goal') {
            eventIcon = '<span class="text-green-400">⚽ BUT!</span>';
        } else if (event.event_type === 'yellow_card') {
            eventIcon = '<span class="text-yellow-400">🟨 Carton jaune</span>';
        } else if (event.event_type === 'red_card') {
            eventIcon = '<span class="text-red-400">🟥 Carton rouge</span>';
        } else if (event.event_type === 'substitution_in') {
            eventIcon = '<span class="text-blue-400">🔄 Remplacement</span>';
        }

        const teamName = event.team_id == {{ $match->home_team_id }}
            ? '{{ $match->homeTeam->university->short_name }}'
            : '{{ $match->awayTeam->university->short_name }}';

        const playerName = (event.player && event.player.full_name)
            ? event.player.full_name
            : (event.player_name || 'Joueur inconnu');

        eventElement.innerHTML = `
            <div class="flex items-center">
                <div class="mr-2">
                    <span class="text-gray-400 text-xs">${event.minute}'</span>
                </div>
                <div class="mr-2">
                    <div class="w-6 h-6 rounded-full bg-gray-600 flex items-center justify-center">
                        <i class="fas fa-user text-xs"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <div class="font-bold text-sm">
                            ${eventIcon}
                            <span class="ml-1">${playerName}</span>
                        </div>
                        <span class="text-xs text-gray-400">${teamName}</span>
                    </div>
                </div>
            </div>
        `;

        if (eventsContainer.firstChild) {
            eventsContainer.insertBefore(eventElement, eventsContainer.firstChild);
        } else {
            eventsContainer.appendChild(eventElement);
        }

        const items = eventsContainer.querySelectorAll('.event-item');
        if (items.length > 3) {
            items[items.length - 1].remove();
        }
    }

    function refreshMatchData{{ $match->id }}() {
        const matchId = {{ $match->id }};
        let lastEventTime = @json($match->matchEvents && $match->matchEvents->count() > 0 ?
            $match->matchEvents->sortByDesc('created_at')->first()->created_at :
            ($match->created_at ?? now()));

        fetch(`/api/matches/${matchId}/live-update?last_event_time=${encodeURIComponent(lastEventTime)}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.match.home_score !== undefined || data.match.away_score !== undefined) {
                        applyScores{{ $match->id }}(data.match.home_score, data.match.away_score);
                    }

                    if (data.match.match_time !== null) {
                        const timeElement = document.getElementById('match-time-' + matchId);
                        if (timeElement) {
                            timeElement.textContent = data.match.match_time + "'";
                        }
                    }

                    applyStats{{ $match->id }}(data.match);

                    if (data.new_events && data.new_events.length > 0) {
                        data.new_events.reverse().forEach(event => addRealtimeEvent{{ $match->id }}(event));
                    }
                }
            })
            .catch(error => {
                console.error('Erreur lors du rafraîchissement des données pour le match ' + matchId + ':', error);
            });
    }

    function setupRealtime{{ $match->id }}() {
        if (!window.Echo) {
            refreshMatchData{{ $match->id }}();
            setInterval(refreshMatchData{{ $match->id }}, 3000);
            return;
        }

        const channel = window.Echo.channel('match.{{ $match->id }}');

        channel
            .listen('MatchEventOccurred', function (payload) {
                if (payload.action === 'deleted') {
                    removeEventById{{ $match->id }}(payload.event_id);
                }

                if (payload.new_event_data || payload.event_type) {
                    addRealtimeEvent{{ $match->id }}(payload.new_event_data || payload);
                }

                if (payload.home_score !== undefined || payload.away_score !== undefined) {
                    applyScores{{ $match->id }}(payload.home_score, payload.away_score);
                }
            })
            .listen('MatchStatusOrStatsUpdated', function (payload) {
                applyStats{{ $match->id }}(payload);

                if (payload.home_score !== undefined || payload.away_score !== undefined) {
                    applyScores{{ $match->id }}(payload.home_score, payload.away_score);
                }
            });

        channel.listenToAll(function (eventName, payload) {
            if (!payload) return;
            if (payload.new_event_data || payload.event_type) {
                addRealtimeEvent{{ $match->id }}(payload.new_event_data || payload);
            }
        });
    }

    setupRealtime{{ $match->id }}();
    @endif
    @endforeach
});
</script>
@endsection
