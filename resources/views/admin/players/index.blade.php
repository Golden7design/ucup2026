@extends('layouts.admin')
@section('header', 'Gestion des Joueurs')
@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        {{-- Bouton Ajouter : bg-blue-600, hover:bg-blue-500 --}}
        <a href="{{ route('admin.players.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition font-semibold">
            Ajouter un Joueur
        </a>

        {{-- Formulaire d'Importation : bg-gray-800, border-gray-700, text-white --}}
        <form action="{{ route('admin.players.bulk-import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4 bg-gray-800 p-3 rounded-xl shadow-md border border-gray-700">
            @csrf
            {{-- Label : text-white --}}
            <label class="block text-sm font-semibold text-white">Importation (.csv):</label>
            {{-- Input file : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
            <input type="file" name="file" class="border border-gray-600 rounded-lg p-1 text-sm text-white bg-gray-700 focus:ring-blue-400 focus:border-blue-400">
            {{-- Bouton Importer : bg-blue-600, hover:bg-blue-500 --}}
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition text-sm font-semibold">
                Importer
            </button>
        </form>
    </div>

    {{-- 🟢 NOUVEAU BLOC : Formulaire de Filtrage et Recherche 🟢 --}}
    {{-- Conteneur : bg-gray-800, border-gray-700 --}}
    <div class="bg-gray-800 p-6 rounded-xl shadow-lg mb-6 border border-gray-700">
        <form method="GET" action="{{ route('admin.players.index') }}" class="flex flex-wrap items-end gap-4">
            {{-- Barre de recherche dynamique --}}
            <div class="flex-1 min-w-[200px]">
                {{-- Label : text-white --}}
                <label for="search" class="block text-sm font-semibold text-white">Rechercher (Nom/Prénom/Numéro/Poste)</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input
                    type="text"
                    name="search"
                    id="search"
                    value="{{ request('search') }}"
                    placeholder="Entrez un nom, prénom, numéro ou poste"
                    class="mt-1 block w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white"
                >
            </div>

            {{-- Filtre par Équipe --}}
            <div class="w-full md:w-auto min-w-[200px]">
                <label for="team_id" class="block text-sm font-semibold text-white">Filtrer par Équipe</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <select name="team_id" id="team_id" class="mt-1 block w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white">
                    <option value="">Toutes les équipes</option>
                    @foreach($teams as $team)
                        <option
                            value="{{ $team->id }}"
                            {{ request('team_id') == $team->id ? 'selected' : '' }}
                        >
                            {{ $team->name }} ({{ $team->university->short_name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex space-x-2">
                {{-- Bouton Appliquer : bg-blue-600, hover:bg-blue-500 --}}
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition font-semibold">
                    Appliquer les filtres
                </button>
                {{-- Bouton Réinitialiser : bg-gray-700, text-white, hover:bg-gray-600 --}}
                <a href="{{ route('admin.players.index') }}" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600 font-semibold">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>
    {{-- 🔴 FIN DU NOUVEAU BLOC 🔴 --}}

    @if($players->isEmpty())
        {{-- Message d'absence : bg-gray-800, text-gray-400, border-l-4 border-blue-500 --}}
        <div class="bg-gray-800 p-6 rounded-xl shadow-md text-center border-l-4 border-blue-500 border border-gray-700">
            <p class="text-gray-400 font-medium">Aucun joueur n'a été trouvé pour le moment avec ces critères.</p>
        </div>
    @else
        {{-- Table Container : bg-gray-800, border-gray-700 --}}
        <div class="bg-gray-800 rounded-xl shadow-lg overflow-x-auto border border-gray-700">
            {{-- Table Head : bg-gray-700, text-gray-300 --}}
            <table class="min-w-full divide-y divide-gray-700" id="playersTable">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            Photo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            Nom & Prénom
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            Numéro
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            Poste
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            Équipe (Université)
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                {{-- Table Body : bg-gray-800, divide-gray-700, text-gray-200 --}}
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @foreach($players as $player)
                        {{-- Row Hover : hover:bg-gray-700 --}}
                        <tr class="player-row hover:bg-gray-700 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{-- Image : border-gray-600 --}}
                                <img
                                    src="{{ $player->photo_url }}"
                                    alt="{{ $player->full_name }}"
                                    class="h-10 w-10 object-cover rounded-full border-2 border-gray-600 shadow-sm"
                                >
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white searchable" data-search="{{ strtolower($player->last_name) }} {{ strtolower($player->first_name) }}">
                                {{ $player->last_name }} <strong class="text-blue-400">{{ $player->first_name }}</strong>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 searchable" data-search="{{ strtolower($player->jersey_number) }}">
                                {{-- Badge Numéro : bg-blue-700, text-white --}}
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-700 text-white">
                                    #{{ $player->jersey_number }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 searchable" data-search="{{ strtolower($player->position) }}">
                                {{ $player->position }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300 searchable" data-search="{{ strtolower($player->team->name) }} {{ strtolower($player->team->university->short_name) }}">
                                <span class="font-medium text-gray-200">{{ $player->team->name }}</span> ({{ $player->team->university->short_name }})
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                {{-- Lien Éditer : text-blue-400 --}}
                                <a href="{{ route('admin.players.edit', $player) }}" class="text-blue-400 hover:text-blue-300 transition font-semibold">
                                    Éditer
                                </a>
                                {{-- Lien Supprimer : text-red-400 --}}
                                <form action="{{ route('admin.players.destroy', $player) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition font-semibold">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-white">
            {{ $players->links() }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const playerRows = document.querySelectorAll('.player-row');

        // Recherche dynamique côté client
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            playerRows.forEach(row => {
                const searchableCells = row.querySelectorAll('.searchable');
                let isVisible = false;

                if (searchTerm === '') {
                    isVisible = true;
                } else {
                    searchableCells.forEach(cell => {
                        // Utilise l'attribut data-search pour la recherche
                        const cellContent = cell.getAttribute('data-search');
                        if (cellContent && cellContent.includes(searchTerm)) {
                            isVisible = true;
                        }
                    });
                }

                row.style.display = isVisible ? '' : 'none';
            });
        });
    });
</script>
@endsection