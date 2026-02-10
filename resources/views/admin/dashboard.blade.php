@extends('layouts.admin')

@section('header', 'Tableau de bord')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- CARD UNIVERSITÉS (Blue) --}}
    <div class="bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-blue-500 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                {{-- Label : text-gray-400 --}}
                <p class="text-sm text-gray-400 uppercase font-semibold">Universités</p>
                {{-- Stat : text-white --}}
                <p class="text-3xl font-bold text-white">{{ $stats['total_universities'] }}</p>
            </div>
            {{-- Icone : bg-blue-600 --}}
            <div class="text-blue-200 bg-blue-600 p-3 rounded-full">
                <i class="fas fa-university text-2xl text-white"></i>
            </div>
        </div>
    </div>

    {{-- CARD ÉQUIPES (Green) --}}
    <div class="bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-green-500 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400 uppercase font-semibold">Équipes</p>
                <p class="text-3xl font-bold text-white">{{ $stats['total_teams'] }}</p>
            </div>
            {{-- Icone : bg-green-600 --}}
            <div class="text-green-200 bg-green-600 p-3 rounded-full">
                <i class="fas fa-users text-2xl text-white"></i>
            </div>
        </div>
    </div>

    {{-- CARD JOUEURS (Yellow/Amber) --}}
    <div class="bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-yellow-500 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400 uppercase font-semibold">Joueurs</p>
                <p class="text-3xl font-bold text-white">{{ $stats['total_players'] }}</p>
            </div>
            {{-- Icone : bg-yellow-600 --}}
            <div class="text-yellow-200 bg-yellow-600 p-3 rounded-full">
                <i class="fas fa-user-friends text-2xl text-white"></i>
            </div>
        </div>
    </div>

    {{-- CARD MATCHS JOUÉS (Purple) --}}
    <div class="bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-purple-500 border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400 uppercase font-semibold">Matchs Joués</p>
                {{-- Stat : text-white --}}
                <p class="text-3xl font-bold text-white">{{ $stats['matches_played'] }} / {{ $stats['total_matches'] }}</p>
            </div>
            {{-- Icone : bg-purple-600 --}}
            <div class="text-purple-200 bg-purple-600 p-3 rounded-full">
                <i class="fas fa-futbol text-2xl text-white"></i>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-8">
    {{-- BLOC PROCHAINS MATCHS / LIVE --}}
    <div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">
        {{-- Titre : text-white, icon text-blue-400 --}}
        <h3 class="text-lg font-bold text-white mb-4 flex items-center border-b border-gray-700 pb-3">
            <i class="fas fa-clock mr-2 text-blue-400"></i> Prochains Matchs
        </h3>
        <div class="space-y-4">
            @forelse($upcomingMatches as $match)
            {{-- Items : text-white, border-gray-700 --}}
            <div class="flex items-center justify-between border-b border-gray-700 pb-2 last:border-0">
                <div class="flex items-center space-x-3">
                    {{-- Date : bg-gray-700, text-gray-300 --}}
                    <span class="text-xs font-bold text-gray-300 bg-gray-700 px-2 py-1 rounded">
                        {{ $match->match_date->format('d/m H:i') }}
                    </span>
                    {{-- Noms d'équipes : text-white --}}
                    <span class="font-semibold text-sm text-white">
                        {{ $match->homeTeam->university->short_name }} vs {{ $match->awayTeam->university->short_name }}
                    </span>
                </div>
                {{-- Lien : text-blue-400, hover:text-blue-300 --}}
                <a href="{{ route('admin.matches.edit', $match) }}" class="text-blue-400 hover:text-blue-300 text-sm">
                    Gérer
                </a>
            </div>
            @empty
            {{-- Message d'absence : text-gray-400 --}}
            <p class="text-gray-400 text-sm italic">Aucun match programmé prochainement.</p>
            @endforelse
        </div>
        
        @if($liveMatches->count() > 0)
        {{-- Séparateur : border-gray-700 --}}
        <div class="mt-6 pt-6 border-t border-gray-700">
            {{-- Titre Live : text-red-400 --}}
            <h3 class="text-lg font-bold text-red-400 mb-4 flex items-center animate-pulse">
                <i class="fas fa-circle mr-2"></i> En Direct Actuellement
            </h3>
            @foreach($liveMatches as $match)
            {{-- Match Live Box : bg-red-900/20, border-red-900, text-white --}}
            <div class="bg-red-900/20 border border-red-900 rounded-lg p-3 mb-2 flex justify-between items-center text-white">
                <div>
                    <span class="font-bold">{{ $match->homeTeam->university->short_name }}</span>
                    {{-- Score : text-red-400 --}}
                    <span id="admin-live-score-{{ $match->id }}" class="mx-2 font-mono text-xl font-bold text-red-400">{{ $match->home_score }} - {{ $match->away_score }}</span>
                    <span class="font-bold">{{ $match->awayTeam->university->short_name }}</span>
                </div>
                {{-- Bouton Live Center : bg-red-600, hover:bg-red-500 --}}
                <a href="{{ route('admin.live.index', $match) }}" class="bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-semibold hover:bg-red-500 transition">
                    Live Center
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- BLOC ACTIONS RAPIDES --}}
    <div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">
        {{-- Titre : text-white --}}
        <h3 class="text-lg font-bold text-white mb-4">Actions Rapides</h3>
        <div class="grid grid-cols-2 gap-4">
            {{-- Liens d'actions : bg-gray-700, border-gray-600, hover:bg-gray-600, text-white --}}
            <a href="{{ route('admin.matches.create') }}" class="p-4 border border-gray-600 rounded-lg bg-gray-700 hover:bg-gray-600 text-center transition group">
                <i class="fas fa-plus-circle text-3xl text-blue-400 mb-2 group-hover:scale-110 transition-transform"></i>
                <p class="font-semibold text-white">Nouveau Match</p>
            </a>
            <a href="{{ route('admin.players.create') }}" class="p-4 border border-gray-600 rounded-lg bg-gray-700 hover:bg-gray-600 text-center transition group">
                <i class="fas fa-user-plus text-3xl text-green-400 mb-2 group-hover:scale-110 transition-transform"></i>
                <p class="font-semibold text-white">Ajouter Joueur</p>
            </a>
            <a href="{{ route('admin.teams.create') }}" class="p-4 border border-gray-600 rounded-lg bg-gray-700 hover:bg-gray-600 text-center transition group">
                <i class="fas fa-users text-3xl text-purple-400 mb-2 group-hover:scale-110 transition-transform"></i>
                <p class="font-semibold text-white">Nouvelle Équipe</p>
            </a>
            <a href="{{ route('admin.live.index') }}" class="p-4 border border-gray-600 rounded-lg bg-gray-700 hover:bg-gray-600 text-center transition group">
                <i class="fas fa-broadcast-tower text-3xl text-red-400 mb-2 group-hover:scale-110 transition-transform"></i>
                <p class="font-semibold text-white">Gérer le Live</p>
            </a>
            <a href="{{ route('admin.users.index') }}" class="p-4 border border-gray-600 rounded-lg bg-gray-700 hover:bg-gray-600 text-center transition group">
                <i class="fas fa-users-cog text-3xl text-yellow-400 mb-2 group-hover:scale-110 transition-transform"></i>
                <p class="font-semibold text-white">Gestion Utilisateurs</p>
            </a>
        </div>
    </div>
</div>

{{-- BLOC OUTILS DE MAINTENANCE --}}
<div class="bg-gray-800 p-6 rounded-xl shadow-lg mt-8 border border-gray-700">
    {{-- Titre : text-blue-400, border-gray-700 --}}
    <h2 class="text-xl font-semibold mb-4 text-blue-400 border-b border-gray-700 pb-2">Outils de Maintenance</h2>
    
    {{-- Texte : text-gray-400 --}}
    <p class="mb-4 text-gray-400">
        Cliquez sur ce bouton pour forcer le recalcul complet des classements de toutes les équipes basé sur les résultats de matchs enregistrés. 
    </p>
    
    <form action="{{ route('admin.standings.recalculate') }}" method="POST">
        @csrf
        {{-- Bouton : bg-red-600, hover:bg-red-500 --}}
        <button type="submit" 
                class="bg-red-600 hover:bg-red-500 text-white font-bold py-2 px-4 rounded-lg transition duration-200 shadow-md flex items-center"
                onclick="return confirm('Êtes-vous sûr de vouloir RECALCULER TOUS LES CLASSEMENTS ? Cette action ne peut pas être annulée.')">
            <i class="fas fa-sync-alt mr-2"></i>
            Recalculer les Classements
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.Echo) {
            console.warn('[Realtime] Echo non initialisé sur le dashboard admin.');
            return;
        }

        const liveMatchIds = @json($liveMatches->pluck('id'));

        function updateScoreElement(matchId, homeScore, awayScore) {
            const scoreEl = document.getElementById(`admin-live-score-${matchId}`);
            if (!scoreEl) return;

            const current = scoreEl.textContent.split('-').map(part => part.trim());
            const currentHome = current[0] ?? '0';
            const currentAway = current[1] ?? '0';

            const nextHome = homeScore !== undefined && homeScore !== null ? homeScore : currentHome;
            const nextAway = awayScore !== undefined && awayScore !== null ? awayScore : currentAway;

            scoreEl.textContent = `${nextHome} - ${nextAway}`;
        }

        liveMatchIds.forEach(function (matchId) {
            window.Echo.channel(`match.${matchId}`)
                .listen('MatchEventOccurred', function (payload) {
                    if (payload.home_score !== undefined || payload.away_score !== undefined) {
                        updateScoreElement(matchId, payload.home_score, payload.away_score);
                    }
                })
                .listen('MatchStatusOrStatsUpdated', function (payload) {
                    if (payload.home_score !== undefined || payload.away_score !== undefined) {
                        updateScoreElement(matchId, payload.home_score, payload.away_score);
                    }
                });
        });
    });
</script>
@endsection
