@extends('layouts.admin')

@section('title', 'Gestion des Matchs')

@section('content')

<div class="flex justify-between items-center mb-6">
    {{-- Suppression de text-gray-800 car le body est déjà text-white --}}
    <h1 class="text-3xl font-bold text-gray-100">Liste des Matchs</h1>
    <a href="{{ route('admin.matches.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md transition duration-150 ease-in-out">
        <i class="fas fa-plus mr-2"></i> Ajouter un Match
    </a>
</div>

{{-- SECTION DU FILTRE DE STATUT --}}
{{-- bg-gray-800 direct, border-gray-700 --}}
<div class="bg-gray-800 shadow-lg rounded-lg p-6 mb-6 border border-gray-700">
    <form method="GET" action="{{ route('admin.matches.index') }}" class="flex items-center space-x-4">
        {{-- Label : text-gray-300 --}}
        <label for="status-filter" class="text-gray-300 font-medium">Filtrer par statut:</label>
        {{-- Select : bg-gray-700, text-gray-200, border-gray-600 --}}
        <select name="status" id="status-filter" class="form-select border-gray-600 bg-gray-700 text-gray-200 rounded-md shadow-sm" onchange="this.form.submit()">
            <option value="all" @if(empty($statusFilter) || $statusFilter === 'all') selected @endif>Tous les matchs</option>
            <option value="scheduled" @if(isset($statusFilter) && $statusFilter === 'scheduled') selected @endif>Planifié</option>
            <option value="live" @if(isset($statusFilter) && $statusFilter === 'live') selected @endif>En direct</option>
            <option value="halftime" @if(isset($statusFilter) && $statusFilter === 'halftime') selected @endif>Mi-temps</option>
            <option value="finished" @if(isset($statusFilter) && $statusFilter === 'finished') selected @endif>Terminé</option>
            <option value="postponed" @if(isset($statusFilter) && $statusFilter === 'postponed') selected @endif>Reporté</option>
            <option value="cancelled" @if(isset($statusFilter) && $statusFilter === 'cancelled') selected @endif>Annulé</option>
        </select>
    </form>
</div>
{{-- FIN DU FILTRE --}}

{{-- MESSAGE DE SUCCÈS (Gardons-le en clair pour un meilleur contraste d'alerte) --}}
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif

{{-- TABLEAU DES MATCHS --}}
{{-- bg-gray-800 direct, border-gray-700 --}}
<div class="bg-gray-800 shadow-lg rounded-lg mb-6 border border-gray-700">
    {{-- border-b-gray-700 --}}
    <div class="p-4 border-b border-gray-700">
        {{-- Titre : text-blue-400 --}}
        <h6 class="text-lg font-semibold text-blue-400">Tableau des Matchs</h6>
    </div>
    <div class="overflow-x-auto">
        {{-- divide-gray-700 --}}
        <table class="min-w-full divide-y divide-gray-700">
            {{-- Header : bg-gray-700 --}}
            <thead class="bg-gray-700">
                <tr>
                    {{-- Text : text-gray-300 --}}
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Match</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date & Heure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Lieu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            {{-- Body : bg-gray-800, divide-gray-700 --}}
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @forelse ($matches as $match)
                <tr>
                    {{-- Texte par défaut : text-gray-400 --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $match->id }}</td>
                    {{-- Titre de match : text-gray-100, Sous-texte : text-gray-400 --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100">
                        {{ $match->homeTeam->university->name }} vs {{ $match->awayTeam->university->name }}
                        <span class="text-xs block text-gray-400">({{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }})</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ \Carbon\Carbon::parse($match->match_date)->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $match->venue ?? 'N/A' }}</td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($match->match_type === 'tournament')
                            {{-- Badge Tournoi : Utilisation des classes dark directes --}}
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-800 text-blue-100">Tournoi</span>
                        @else
                            {{-- Badge Amical : Utilisation des classes dark directes --}}
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-800 text-yellow-100">Amical</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{-- Utilisation de la fonction avec les classes dark --}}
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ match_status_class_tailwind($match->status) }}">
                            {{ ucfirst($match->status) }}
                        </span>
                    </td>
                    {{-- Score : text-gray-100 --}}
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-100 font-semibold">{{ $match->home_score ?? '0' }} - {{ $match->away_score ?? '0' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-3">
                            {{-- Icones : text-blue-400, text-gray-400, text-yellow-400, text-red-400 --}}
                            <a href="{{ route('admin.matches.edit', $match) }}" class="text-blue-400 hover:text-blue-300" title="Modifier le match">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.matches.lineup', $match) }}" class="text-gray-400 hover:text-gray-200" title="Gérer la composition">
                                <i class="fas fa-users"></i>
                            </a>
                            
                            @if($match->status === 'live' || $match->status === 'halftime')
                                <a href="{{ route('admin.live.show', $match) }}" class="text-yellow-400 hover:text-yellow-300" title="Gérer le live / les événements">
                                    <i class="fas fa-bullhorn"></i>
                                </a>
                            @endif
                            
                            <form action="{{ route('admin.matches.destroy', $match) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce match?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300" title="Supprimer le match">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    {{-- Texte d'absence de données : text-gray-400 --}}
                    <td colspan="8" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-400">Aucun match trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Liens de pagination --}}
    {{-- border-t-gray-700 --}}
    <div class="p-4 border-t border-gray-700">
        <div class="flex justify-center">
            {{ $matches->links('pagination::tailwind') }}
        </div>
    </div>
    
</div>

@endsection

{{-- Fonction utilitaire pour le statut (COULEURS DARK DÉFINIES) --}}
@php
function match_status_class_tailwind($status) {
    return [
        'scheduled' => 'bg-blue-800 text-blue-100', // bg-blue-100 text-blue-800 supprimé
        'live' => 'bg-red-800 text-red-100 animate-pulse', // bg-red-100 text-red-800 supprimé
        'halftime' => 'bg-yellow-800 text-yellow-100', // bg-yellow-100 text-yellow-800 supprimé
        'finished' => 'bg-green-800 text-green-100', // bg-green-100 text-green-800 supprimé
        'postponed' => 'bg-gray-700 text-gray-200', // bg-gray-100 text-gray-800 supprimé
        'cancelled' => 'bg-gray-500 text-gray-100', 
    ][$status] ?? 'bg-indigo-800 text-indigo-100'; // bg-indigo-100 text-indigo-800 supprimé
}
@endphp