@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- Titre --}}
    <h1 class="text-3xl font-bold mb-6 text-white">
        <i class="fas fa-medal text-yellow-500 mr-3"></i>
        Classement des Meilleurs Joueurs
    </h1>

    {{-- Conteneur du Tableau (Fond sombre) --}}
    <div class="bg-gray-800 rounded-lg shadow-xl p-6 border border-gray-700">

        @if($topPlayers->isEmpty())
            <div class="p-8 text-center text-gray-400">
                <i class="fas fa-users-slash text-6xl text-gray-600 mb-4"></i>
                <p class="text-lg">Aucun joueur trouvé pour le classement.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                {{-- Tableau --}}
                <table class="min-w-full divide-y divide-gray-700">
                    
                    {{-- En-tête du Tableau --}}
                    <thead class="bg-gray-700 text-gray-300 text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3 text-left">#</th>
                            <th class="px-6 py-3 text-left">Nom du Joueur</th>
                            <th class="px-6 py-3 text-center">Buts</th>
                            {{-- Ajoutez d'autres colonnes si nécessaire (ex: Équipe, Passes) --}}
                        </tr>
                    </thead>
                    
                    {{-- Corps du Tableau --}}
                    <tbody class="divide-y divide-gray-700 text-white">
                        @foreach ($topPlayers as $index => $player)
                            {{-- Ligne : bg-gray-800, hover:bg-gray-700 --}}
                            <tr class="hover:bg-gray-700 transition">
                                <td class="px-4 py-3 text-sm text-gray-400 text-left">{{ $index + 1 }}</td>
                                {{-- Nom du joueur (lien si possible) --}}
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold">
                                    {{-- J'ajoute un lien par bonne pratique, en supposant que la route existe --}}
                                    <a href="{{ route('players.show', $player) }}" class="text-white hover:text-blue-400">
                                        {{ $player->full_name ?? $player->name }}
                                    </a>
                                </td>
                                {{-- Buts : texte en couleur pour l'importance --}}
                                <td class="px-6 py-3 text-center text-lg font-bold text-green-400">{{ $player->goals ?? 0 }}</td>
                                {{-- Ajoutez d'autres données ici --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection