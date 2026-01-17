@extends('layouts.admin')
@section('header')
    <div class="flex justify-between items-center">
        {{-- Header : text-white --}}
        <span class="text-white">Gestion des Équipes</span>
        {{-- Bouton Ajouter : bg-blue-600, hover:bg-blue-500 --}}
        <a href="{{ route('admin.teams.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-500 transition font-semibold">
            <i class="fas fa-plus mr-2"></i> Ajouter
        </a>
    </div>
@endsection
@section('content')
{{-- Conteneur principal : bg-gray-800, border-gray-700 --}}
<div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700">
    <div class="p-4 border-b border-gray-700">
        <div class="relative">
            {{-- Input Recherche : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
            <input 
                type="text" 
                id="searchInput" 
                class="w-full px-4 py-2 border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 bg-gray-700 text-white"
                placeholder="Rechercher une équipe, une université ou un slogan..."
            >
            {{-- Icone : text-gray-400 --}}
            <div class="absolute right-3 top-2.5 text-gray-400">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        {{-- Table Head : bg-gray-700, text-gray-300 --}}
        <table class="min-w-full divide-y divide-gray-700" id="teamsTable">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider w-24">Logo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider w-48">Équipe</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Université</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-300 uppercase tracking-wider w-24">Joueurs</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider w-24">Actions</th>
                </tr>
            </thead>
            {{-- Table Body : bg-gray-800, divide-gray-700, text-gray-200 --}}
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse($teams as $team)
                {{-- Row Hover : hover:bg-gray-700 --}}
                <tr class="team-row hover:bg-gray-700 transition duration-150">
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center justify-center">
                            @if($team->university && $team->university->logo)
                                {{-- Image : border-gray-600 --}}
                                <img class="h-10 w-10 object-contain border border-gray-600 rounded-full p-0.5" src="{{ asset('storage/' . $team->university->logo) }}" alt="{{ $team->university->short_name }} Logo">
                            @else
                                {{-- Placeholder : bg-gray-700, text-gray-400 --}}
                                <div class="h-10 w-10 bg-gray-700 rounded-full flex items-center justify-center border border-gray-600">
                                    <i class="fas fa-users text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap searchable" data-search="{{ strtolower($team->name) }} {{ strtolower($team->slogan) }}">
                        <div class="flex flex-col">
                            {{-- Nom : text-white --}}
                            <div class="text-sm font-medium text-white">{{ $team->name }}</div>
                            {{-- Slogan : text-gray-400 --}}
                            <div class="text-xs text-gray-400">{{ $team->slogan }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-300 searchable" data-search="{{ strtolower($team->university->name) }} {{ strtolower($team->university->short_name) }}">
                        {{ $team->university->name }} (<strong class="text-blue-400">{{ $team->university->short_name }}</strong>)
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-bold text-blue-400">
                        {{ $team->players_count ?? $team->players->count() }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                        {{-- Lien Éditer : text-blue-400, hover:text-blue-300 --}}
                        <a href="{{ route('admin.teams.edit', $team) }}" class="text-blue-400 hover:text-blue-300 mr-3 transition">
                            <i class="fas fa-edit"></i>
                        </a>
                        {{-- Bouton Supprimer : text-red-400, hover:text-red-300 --}}
                        <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="inline-block" onsubmit="return confirm('ATTENTION : Supprimer une équipe supprime tous les joueurs et matchs associés. Confirmez ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    {{-- Message d'absence : text-gray-400 --}}
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                        Aucune équipe enregistrée pour l'instant.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-700 text-white">
        {{ $teams->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const teamRows = document.querySelectorAll('.team-row');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            teamRows.forEach(row => {
                const searchableCells = row.querySelectorAll('.searchable');
                let isVisible = false;

                // Vérifie si l'élément contient l'un des termes de recherche
                searchableCells.forEach(cell => {
                    const cellContent = cell.getAttribute('data-search');
                    if (cellContent && cellContent.includes(searchTerm)) {
                        isVisible = true;
                    }
                });

                row.style.display = isVisible ? '' : 'none';
            });
        });
    });
</script>
@endsection