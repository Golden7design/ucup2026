@extends('layouts.app')

@section('title', $player->full_name . ' - Fiche Joueur')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Conteneur Principal : bg-gray-800, border-gray-700, shadow-xl --}}
    {{-- Anciennement bg-white --}}
    <div class="bg-gray-800 rounded-lg shadow-xl p-8 mb-6 border border-gray-700">
        
        {{-- En-tête du Joueur --}}
        <div class="text-center mb-6">
            
            {{-- 🟢 Bloc de la Photo du Joueur (Taille Augmentée) 🟢 --}}
            <div class="mb-4 mx-auto">
                <img 
                    src="{{ $player->photo_url }}" 
                    alt="Photo de {{ $player->full_name }}" 
                    {{-- La bordure bleue est conservée pour l'accentuation --}}
                    class="w-48 h-48 object-cover rounded-full border-4 border-blue-600 shadow-xl mx-auto"
                >
            </div>
            
            {{-- Nom du joueur : text-white --}}
            {{-- Anciennement text-gray-900 --}}
            <h1 class="text-4xl font-extrabold text-white mb-2">{{ $player->full_name }}</h1>
            {{-- Position/Numéro : text-gray-400 --}}
            {{-- Anciennement text-gray-600 --}}
            <p class="text-lg text-gray-400 mb-4">{{ $player->position }} - N°{{ $player->jersey_number }}</p>
            
            {{-- Lien vers l'équipe : text-blue-400 --}}
            <a href="{{ route('teams.show', $player->team) }}" class="inline-flex items-center text-blue-400 hover:text-blue-500 transition">
                @if($player->team->university->logo)
                    <img src="{{ asset('storage/' . $player->team->university->logo) }}" alt="{{ $player->team->university->name }}" class="h-8 w-8 object-contain mr-2">
                @endif
                {{ $player->team->university->name }} ({{ $player->team->name }})
            </a>
            
        </div>
        
        {{-- Ligne de séparation : border-gray-700 --}}
        <hr class="my-6 border-gray-700">

        {{-- Tableau des Statistiques --}}
        {{-- Titre de section : text-white, border-gray-700 --}}
        <h2 class="text-2xl font-bold mb-4 border-b border-gray-700 pb-2 text-white">Statistiques Carrière</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            
            {{-- Stat Box : border-gray-700, bg-gray-700 --}}
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-700">
                <p class="text-3xl font-bold text-green-500">{{ $stats['goals'] }}</p>
                {{-- Label : text-gray-400 --}}
                <p class="text-gray-400 text-sm">Buts</p>
            </div>
            
            {{-- Stat Box : border-gray-700, bg-gray-700 --}}
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-700">
                <p class="text-3xl font-bold text-blue-500">{{ $stats['assists'] }}</p>
                {{-- Label : text-gray-400 --}}
                <p class="text-gray-400 text-sm">Passes Décisives</p>
            </div>
            
            {{-- Stat Box : border-gray-700, bg-gray-700 --}}
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-700">
                {{-- Valeur : text-white --}}
                <p class="text-3xl font-bold text-white">{{ $stats['matches_played'] }}</p>
                {{-- Label : text-gray-400 --}}
                <p class="text-gray-400 text-sm">Matchs Joués</p>
            </div>
            
            {{-- Stat Box Cartons : border-gray-700, bg-gray-700 --}}
            <div class="p-4 border border-gray-700 rounded-lg bg-gray-700">
                <p class="text-3xl font-bold text-yellow-400">{{ $stats['yellow_cards'] }}</p>
                <p class="text-gray-400 text-sm mb-1">Cartons Jaunes</p>
                <p class="text-3xl font-bold text-red-500">{{ $stats['red_cards'] }}</p>
                <p class="text-gray-400 text-sm">Cartons Rouges</p>
            </div>
            
        </div>

    </div>
    
    {{-- Derniers événements --}}
    {{-- Conteneur d'événements : bg-gray-800, border-gray-700 --}}
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 mt-6 border border-gray-700 text-white">
        <h3 class="text-xl font-bold mb-4">Événements Récents du Joueur</h3>

        @if($recentEvents->isEmpty()) 
            {{-- Texte gris clair --}}
            <p class="text-gray-400">Le joueur n'a participé à aucun événement récemment.</p>
        @else
            <ul class="space-y-3">
                @foreach($recentEvents as $event)
                    {{-- Ligne d'événement : border-gray-700 --}}
                    <li class="flex items-start p-3 border-b border-gray-700 last:border-b-0">
                        {{-- Texte gris clair --}}
                        <span class="w-12 text-right text-gray-400 font-semibold">{{ $event->minute }}'</span>
                        <span class="mx-4 text-gray-600">|</span>
                        
                        <div class="flex-1">
                            @php
                                $eventTeam = $event->team;
                            @endphp
                            
                            {{-- Détails du match : text-gray-500 -> text-gray-400 --}}
                            <p class="text-sm text-gray-400">
                                Match : 
                                <a href="{{ route('matches.show', $event->match) }}" class="font-semibold hover:underline text-white hover:text-blue-400">
                                    {{ $event->match->homeTeam->university->short_name }} vs {{ $event->match->awayTeam->university->short_name }}
                                </a>
                                ({{ $event->match->match_date->format('d/m/Y') }})
                            </p>
                            
                            {{-- Description de l'événement : text-white --}}
                            <p class="font-medium mt-1 text-white">
                                @if($event->event_type === 'goal')
                                    ⚽ But marqué
                                    @if($event->assistPlayer)
                                        (Assisté par <a href="{{ route('players.show', $event->assistPlayer) }}" class="font-bold hover:underline text-blue-400 hover:text-blue-500">{{ $event->assistPlayer->full_name }}</a>)
                                    @endif
                                @elseif($event->event_type === 'yellow_card')
                                    🟨 Carton jaune
                                @elseif($event->event_type === 'red_card')
                                    🟥 Carton rouge
                                @elseif($event->event_type === 'substitution')
                                    🔁 Remplacement
                                @endif
                            </p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div> 
@endsection