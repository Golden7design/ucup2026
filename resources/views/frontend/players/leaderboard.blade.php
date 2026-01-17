@extends('layouts.app')

@section('title', 'Classement des Joueurs')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Titre principal en blanc --}}
    <h1 class="text-3xl font-bold mb-8 text-white">
        <i class="fas fa-trophy text-yellow-500 mr-3"></i>
        Classements Individuels des Joueurs
    </h1>

    <div class="grid md:grid-cols-2 gap-10">
        
        {{-- 🏆 TABLEAU DES MEILLEURS BUTEURS --}}
        {{-- Fond : bg-gray-800 --}}
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
            {{-- Titre : Texte blanc/coloré --}}
            <h2 class="text-2xl font-extrabold mb-6 text-red-500 border-b border-gray-700 pb-2 flex items-center">
                <i class="fas fa-futbol mr-3"></i> Top 10 des Buteurs
            </h2>
            
            <div class="overflow-x-auto">
                {{-- Séparateur de tableau en gris foncé --}}
                <table class="min-w-full divide-y divide-gray-700">
                    {{-- En-tête du tableau : bg-red-900, Texte gris clair --}}
                    <thead class="bg-red-900 text-gray-300 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-3 py-3 text-left">#</th>
                            <th class="px-6 py-3 text-left">Joueur</th>
                            <th class="px-6 py-3 text-center">Équipe</th>
                            <th class="px-3 py-3 text-center font-bold">Buts</th>
                        </tr>
                    </thead>
                    {{-- Corps du tableau : bg-gray-800 --}}
                    <tbody class="divide-y divide-gray-700 text-white">
                        @forelse($topScorers as $index => $player)
                        {{-- Hover : bg-gray-700 --}}
                        <tr class="hover:bg-gray-700 transition">
                            {{-- Texte gris pour les détails --}}
                            <td class="px-3 py-3 text-sm text-gray-400 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold">
                                {{-- Lien hover en couleur --}}
                                <a href="{{ route('players.show', $player) }}" class="hover:text-red-500">
                                    {{ $player->full_name ?? $player->first_name . ' ' . $player->last_name }}
                                </a>
                            </td>
                            <td class="px-6 py-3 text-center text-sm">
                                <span class="text-gray-400">{{ $player->team->university->short_name }}</span>
                            </td>
                            {{-- Colonne des Buts : bg-red-900, Texte rouge foncé --}}
                            <td class="px-3 py-3 text-center text-lg font-extrabold text-red-400 bg-red-900">{{ $player->goals_count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">Aucun but n'a été enregistré pour le moment.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- 🏅 TABLEAU DES MEILLEURS PASSEURS --}}
        {{-- Fond : bg-gray-800 --}}
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl border border-gray-700">
            {{-- Titre : Texte blanc/coloré --}}
            <h2 class="text-2xl font-extrabold mb-6 text-blue-500 border-b border-gray-700 pb-2 flex items-center">
                <i class="fas fa-handshake mr-3"></i> Top 10 des Passeurs
            </h2>
            
            <div class="overflow-x-auto">
                {{-- Séparateur de tableau en gris foncé --}}
                <table class="min-w-full divide-y divide-gray-700">
                    {{-- En-tête du tableau : bg-blue-900, Texte gris clair --}}
                    <thead class="bg-blue-900 text-gray-300 text-xs uppercase tracking-wider">
                        <tr>
                            <th class="px-3 py-3 text-left">#</th>
                            <th class="px-6 py-3 text-left">Joueur</th>
                            <th class="px-6 py-3 text-center">Équipe</th>
                            <th class="px-3 py-3 text-center font-bold">Passes</th>
                        </tr>
                    </thead>
                    {{-- Corps du tableau : bg-gray-800 --}}
                    <tbody class="divide-y divide-gray-700 text-white">
                        @forelse($topAssists as $index => $player)
                        {{-- Hover : bg-gray-700 --}}
                        <tr class="hover:bg-gray-700 transition">
                            {{-- Texte gris pour les détails --}}
                            <td class="px-3 py-3 text-sm text-gray-400 text-center">{{ $index + 1 }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold">
                                {{-- Lien hover en couleur --}}
                                <a href="{{ route('players.show', $player) }}" class="hover:text-blue-500">
                                    {{ $player->full_name ?? $player->first_name . ' ' . $player->last_name }}
                                </a>
                            </td>
                            <td class="px-6 py-3 text-center text-sm">
                                <span class="text-gray-400">{{ $player->team->university->short_name }}</span>
                            </td>
                            {{-- Colonne des Passes : bg-blue-900, Texte bleu foncé --}}
                            <td class="px-3 py-3 text-center text-lg font-extrabold text-blue-400 bg-blue-900">{{ $player->assists_count }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-400">Aucune passe décisive n'a été enregistrée pour le moment.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection