@extends('layouts.app')
@section('title', 'Accueil - U-Cup Tournoi')

@section('content')
<div class="bg-gray-800 text-white rounded-lg shadow-2xl p-8 mb-8">
    <div class="text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-yellow-400">
            <i class="fas fa-trophy mr-2"></i>
            U-Cup Tournoi 2026
        </h1>
        <p class="text-xl text-gray-400">Le tournoi universitaire de football de Pointe-Noire</p>
        <div class="mt-6 flex justify-center gap-4">
            <a href="{{ route('matches.live') }}" class="bg-red-600 hover:bg-red-700 px-6 py-3 rounded-xl font-semibold transition shadow-md">
                <i class="fas fa-circle animate-pulse mr-2"></i>Matchs en Direct
            </a>
            <a href="{{ route('standings.index') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-6 py-3 rounded-xl font-semibold transition shadow-md">
                <i class="fas fa-list-alt mr-2"></i>Classement
            </a>
        </div>
    </div>
</div>

{{-- 1. MATCHS EN DIRECT (non modifié) --}}
@if($liveMatches->count() > 0)
<div class="mb-8 p-6 bg-gray-900 rounded-lg shadow-xl border-t-4 border-red-600">
    <h2 class="text-2xl font-bold mb-4 flex items-center text-red-500">
        <i class="fas fa-circle text-red-500 animate-pulse mr-3"></i>
        Matchs en Direct
    </h2>
    <div class="grid md:grid-cols-2 gap-6">
        @foreach($liveMatches as $match)
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 border-l-4 border-red-500 text-white">
            <div class="text-center mb-4">
                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold shadow-md">
                    {{ $match->status === 'halftime' ? 'MI-TEMPS' : 'EN DIRECT' }}
                </span>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex-1 text-center">
                    <div class="mb-2">
                        @if(optional($match->homeTeam->university)->logo)
                            <img src="{{ asset('storage/' . $match->homeTeam->university->logo) }}" alt="{{ $match->homeTeam->university->name }}" class="h-16 w-16 mx-auto object-contain rounded-full border-2 border-gray-700">
                        @else
                            <i class="fas fa-shield-alt text-5xl text-blue-400"></i>
                        @endif
                    </div>
                    <h3 class="font-bold text-lg text-blue-300">{{ optional($match->homeTeam->university)->short_name }}</h3>
                    <p class="text-sm text-gray-400">{{ $match->homeTeam->name }}</p>
                </div>
                <div class="mx-6">
                    <div class="text-5xl font-extrabold text-center text-white">
                        {{ $match->home_score }} - {{ $match->away_score }}
                    </div>
                </div>
                <div class="flex-1 text-center">
                    <div class="mb-2">
                        @if(optional($match->awayTeam->university)->logo)
                            <img src="{{ asset('storage/' . $match->awayTeam->university->logo) }}" alt="{{ $match->awayTeam->university->name }}" class="h-16 w-16 mx-auto object-contain rounded-full border-2 border-gray-700">
                        @else
                            <i class="fas fa-shield-alt text-5xl text-green-400"></i>
                        @endif
                    </div>
                    <h3 class="font-bold text-lg text-blue-300">{{ optional($match->awayTeam->university)->short_name }}</h3>
                    <p class="text-sm text-gray-400">{{ $match->awayTeam->name }}</p>
                </div>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('matches.show', $match) }}" class="text-blue-400 hover:text-blue-300 font-semibold transition">
                    Voir les détails <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid md:grid-cols-3 gap-8 mb-8">
    {{-- 2. MATCHS DU JOUR (CORRECTION DU FORMATAGE DE DATE) --}}
    <div class="md:col-span-1 p-6 bg-gray-800 rounded-lg shadow-xl border-t-4 border-yellow-500">
        <h2 class="text-xl font-bold mb-4 flex items-center text-yellow-500">
            <i class="fas fa-calendar-day mr-3"></i>
            Matchs du Jour (À Venir)
        </h2>
        <div class="space-y-4">
            @forelse($todayUpcomingMatches as $match)
            <div class="bg-gray-700 rounded-lg p-4 text-white hover:bg-gray-600 transition duration-150">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        {{-- CORRECTION: Utilisation de Carbon::parse() --}}
                        {{ \Carbon\Carbon::parse($match->match_date)->format('H:i') }}
                    </span>
                    @if($match->group)
                        <span class="bg-yellow-600 text-white px-2 py-1 rounded text-xs font-semibold">
                            Groupe {{ $match->group }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between text-base">
                    <div class="flex-1 font-semibold">{{ optional($match->homeTeam->university)->short_name }}</div>
                    <div class="mx-2 text-gray-400">vs</div>
                    <div class="flex-1 text-right font-semibold">{{ optional($match->awayTeam->university)->short_name }}</div>
                </div>

                <div class="mt-2 text-sm text-gray-400 text-center">
                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $match->venue }}
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucun match programmé pour le reste de la journée.</p>
            @endforelse
        </div>
    </div>

    {{-- 3. PROCHAINS MATCHS (CORRECTION DU FORMATAGE DE DATE) --}}
    <div class="md:col-span-1 p-6 bg-gray-800 rounded-lg shadow-xl border-t-4 border-blue-600">
        <h2 class="text-xl font-bold mb-4 flex items-center text-blue-400">
            <i class="fas fa-calendar-alt mr-3"></i>
            Prochains Matchs
        </h2>
        <div class="space-y-4">
            @forelse($upcomingMatches as $match)
            <div class="bg-gray-700 rounded-lg p-4 text-white hover:bg-gray-600 transition duration-150">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-400">
                        <i class="fas fa-calendar-check mr-1"></i>
                        {{-- CORRECTION: Utilisation de Carbon::parse() --}}
                        {{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y H:i') }}
                    </span>
                    @if($match->group)
                        <span class="bg-blue-700 text-white px-2 py-1 rounded text-xs font-semibold">
                            Groupe {{ $match->group }}
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between text-base">
                    <div class="flex-1 font-semibold">{{ optional($match->homeTeam->university)->short_name }}</div>
                    <div class="mx-2 text-gray-400">vs</div>
                    <div class="flex-1 text-right font-semibold">{{ optional($match->awayTeam->university)->short_name }}</div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucun match programmé prochainement.</p>
            @endforelse
            <div class="pt-2 text-center">
                <a href="{{ route('matches.index') }}" class="text-blue-400 hover:text-blue-300 text-sm">Voir le calendrier complet <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
        </div>
    </div>

    {{-- 4. CLASSEMENT (non modifié) --}}
    <div class="md:col-span-1 p-6 bg-gray-800 rounded-lg shadow-xl border-t-4 border-green-600">
        <h2 class="text-xl font-bold mb-4 flex items-center text-green-400">
            <i class="fas fa-trophy mr-3"></i>
            Classement (Top 5)
        </h2>
        <div class="space-y-3">
            @forelse($standings as $index => $standing)
            <div class="flex justify-between items-center bg-gray-700 rounded p-3 text-white">
                <div class="flex items-center">
                    <span class="font-bold w-6 text-center text-lg {{ $index < 2 ? 'text-yellow-400' : 'text-gray-400' }}">{{ $index + 1 }}</span>
                    <p class="ml-3 font-semibold">{{ optional($standing->team->university)->short_name ?? $standing->team->name }}</p>
                </div>
                <span class="bg-green-600 px-3 py-1 rounded text-sm font-bold">{{ $standing->points }} pts</span>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Classement non disponible.</p>
            @endforelse
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('standings.index') }}" class="text-green-400 hover:text-green-300 text-sm">Voir le classement complet <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>
</div>

<div class="grid md:grid-cols-2 gap-8 mb-8">
    {{-- 5. RÉSULTATS PASSÉS (non modifié) --}}
    <div class="md:col-span-1 p-6 bg-gray-800 rounded-lg shadow-xl border-t-4 border-purple-600">
        <h2 class="text-xl font-bold mb-4 flex items-center text-purple-400">
            <i class="fas fa-history mr-3"></i>
            Résultats Passés
        </h2>
        <div class="space-y-4">
            @forelse($recentMatches as $match)
            <div class="bg-gray-700 rounded-lg shadow p-4 hover:bg-gray-600 transition text-white">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-400">
                        {{-- Le casting dans le modèle devrait fonctionner pour cette section si elle est correcte --}}
                        {{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y') }}
                    </span>
                    <span class="bg-purple-600 text-white px-2 py-1 rounded text-xs font-semibold">
                        Terminé
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="font-semibold {{ $match->home_score > $match->away_score ? 'text-green-400' : '' }}">
                            {{ optional($match->homeTeam->university)->short_name }}
                        </p>
                    </div>
                    <div class="mx-4 text-xl font-bold text-white">
                        {{ $match->home_score }} - {{ $match->away_score }}
                    </div>
                    <div class="flex-1 text-right">
                        <p class="font-semibold {{ $match->away_score > $match->home_score ? 'text-green-400' : '' }}">
                            {{ optional($match->awayTeam->university)->short_name }}
                        </p>
                    </div>
                </div>

                <div class="mt-2 text-center">
                    <a href="{{ route('matches.show', $match) }}" class="text-purple-400 hover:text-purple-300 text-sm">
                        Voir le résumé
                    </a>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">Aucun résultat récent disponible.</p>
            @endforelse
        </div>
    </div>

    {{-- 6. MEILLEURS BUTEURS (CORRIGÉ : Utilisation de first_name et last_name) --}}
    <div class="md:col-span-1 p-6 bg-gray-800 rounded-lg shadow-xl border-t-4 border-yellow-500">
        <h2 class="text-xl font-bold mb-4 flex items-center text-yellow-400">
            <i class="fas fa-futbol mr-3"></i>
            Meilleurs Buteurs
        </h2>
        <div class="overflow-x-auto text-white">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Joueur</th>
                        <th class="px-4 py-2 text-left">Équipe</th>
                        <th class="px-4 py-2 text-center">Buts</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topScorers as $index => $player)
                    <tr class="border-t border-gray-700 hover:bg-gray-700 transition">
                        <td class="px-4 py-3">
                            {{-- LOGIQUE DE LA COURONNE --}}
                            @if($index === 0)
                                <i class="fas fa-crown text-yellow-400 text-lg"></i>
                            @else
                                <span class="{{ $index < 3 ? 'text-yellow-400' : 'text-gray-400' }} font-bold">
                                    {{ $index + 1 }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            {{-- CORRECTION ICI : Concaténation de first_name et last_name --}}
                            @php
                                $fullName = trim($player->first_name . ' ' . $player->last_name);
                            @endphp
                            
                            @if (!empty($fullName))
                            <a href="{{ route('players.show', $player) }}" class="font-semibold hover:text-blue-400">
                                {{ $fullName }}
                            </a>
                            @else
                                {{-- Si le nom complet est vide, on utilise le format "Joueur [ID]" --}}
                                <span class="text-gray-400">Joueur {{ $player->id }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-400">
                            @if(optional($player->team)->university)
                                {{ $player->team->university->short_name }}
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="bg-yellow-600 text-white px-3 py-1 rounded-full font-bold shadow-sm">
                                {{ $player->goals }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            Aucun buteur pour le moment
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-center">
            {{-- Vous devrez peut-être créer cette route si elle n'existe pas : 'players.leaderboard' --}}
            <a href="{{ route('players.leaderboard') }}" class="text-yellow-400 hover:text-yellow-300 font-semibold">
                Voir tous les buteurs <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>
@endsection