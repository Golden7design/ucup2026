@extends('layouts.app')

@section('title', $team->university->name . ' - U-Cup')

@section('content')
<div class="grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        {{-- Bloc d'en-tête de l'équipe : bg-gray-800, border-gray-700 --}}
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6 flex items-center gap-6 border border-gray-700">
            <div class="w-24 h-24 flex-shrink-0">
                @if($team->university->logo)
                    <img src="{{ asset('storage/' . $team->university->logo) }}" class="w-full h-full object-contain">
                @else
                    {{-- Icône par défaut en gris foncé --}}
                    <i class="fas fa-shield-alt text-6xl text-gray-500"></i>
                @endif
            </div>
            <div>
                {{-- Titres en blanc --}}
                <h1 class="text-3xl font-bold text-white">{{ $team->university->name }}</h1>
                {{-- Sous-titres en gris clair --}}
                <p class="text-gray-400 text-lg mb-2">{{ $team->name }}</p>
                <div class="flex gap-4 text-sm">
                    {{-- Badges : utiliser une couleur d'arrière-plan plus foncée, textes clairs --}}
                    <span class="bg-blue-900 text-blue-200 px-3 py-1 rounded-full">
                        <i class="fas fa-user-tie mr-1"></i> Coach: {{ $team->coach ?? 'Non défini' }}
                    </span>
                    @if($team->standing)
                    <span class="bg-yellow-900 text-yellow-200 px-3 py-1 rounded-full">
                        <i class="fas fa-trophy mr-1"></i> {{ $team->standing->points }} pts
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Bloc de l'effectif : bg-gray-800 --}}
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
            {{-- En-tête de section : border-gray-700, text-white --}}
            <div class="px-6 py-4 border-b border-gray-700">
                <h2 class="text-xl font-bold text-white">Effectif</h2>
            </div>
            <table class="w-full text-left">
                {{-- En-tête de tableau : bg-gray-700, texte gris clair --}}
                <thead class="bg-gray-700 text-gray-300 text-sm uppercase">
                    <tr>
                        <th class="px-6 py-3">Num</th>
                        <th class="px-6 py-3">Joueur</th>
                        <th class="px-6 py-3">Poste</th>
                        <th class="px-6 py-3 text-center">Buts</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-white">
                    @php
                        // Mise à jour des classes de badges pour le mode sombre
                        $badges = [
                            'Gardien' => 'bg-yellow-900 text-yellow-200',
                            'Défenseur' => 'bg-blue-900 text-blue-200',
                            'Milieu' => 'bg-green-900 text-green-200',
                            'Attaquant' => 'bg-red-900 text-red-200',
                        ];
                        $labels = [
                            'Gardien' => 'Gardien',
                            'Défenseur' => 'Défenseur',
                            'Milieu' => 'Milieu',
                            'Attaquant' => 'Attaquant',
                        ];
                    @endphp

                    @foreach($team->players as $player)
                    {{-- Ligne : hover:bg-gray-700 --}}
                    <tr class="hover:bg-gray-700 transition">
                        {{-- Numéro : text-gray-400 --}}
                        <td class="px-6 py-3 font-bold text-gray-400">{{ $player->jersey_number }}</td>
                        <td class="px-6 py-3">
                            {{-- Lien : text-white, hover:text-blue-400 --}}
                            <a href="{{ route('players.show', $player) }}" class="font-semibold text-white hover:text-blue-400">
                                {{ $player->full_name }}
                            </a>
                        </td>
                        <td class="px-6 py-3">
                            {{-- Badge de position --}}
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $badges[$player->position] ?? 'bg-gray-700 text-gray-300' }}">
                                {{ $labels[$player->position] ?? $player->position }}
                            </span>
                        </td>
                        {{-- Buts : text-green-400 --}}
                        <td class="px-6 py-3 text-center font-bold text-green-400">
                            {{ $player->getGoalsCount() > 0 ? $player->getGoalsCount() : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Colonne latérale (Derniers Matchs) --}}
    <div>
        {{-- Bloc Latéral : bg-gray-800, border-gray-700 --}}
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 sticky top-4 border border-gray-700">
            <h2 class="text-xl font-bold mb-4 text-white">Derniers Matchs</h2>
            <div class="space-y-4">
                @forelse($matches as $match)
                {{-- Carte de match : border-gray-700, bg-gray-700 sur hover --}}
                <div class="border border-gray-700 rounded-lg p-3 hover:bg-gray-700 transition">
                    <div class="text-xs text-gray-400 mb-2 flex justify-between">
                        <span>{{ $match->match_date->format('d/m') }}</span>
                        <span>{{ $match->status === 'finished' ? 'Terminé' : 'À venir' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col items-center w-1/3">
                            <span class="font-bold text-sm text-white">{{ $match->homeTeam->university->short_name }}</span>
                        </div>
                        {{-- Score/VS : bg-gray-700 -> bg-gray-900 --}}
                        <div class="font-bold bg-gray-900 px-2 py-1 rounded text-white">
                            @if($match->status === 'scheduled')
                                VS
                            @else
                                {{ $match->home_score }} - {{ $match->away_score }}
                            @endif
                        </div>
                        <div class="flex flex-col items-center w-1/3">
                            <span class="font-bold text-sm text-white">{{ $match->awayTeam->university->short_name }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-center">Aucun match.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection