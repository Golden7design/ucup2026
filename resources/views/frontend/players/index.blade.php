@extends('layouts.app')

@section('title', 'Joueurs - U-Cup Tournament')

@section('content')
{{-- Suppression des classes de fond claires pour hériter du fond sombre de layouts.app --}}
<div class="max-w-7xl mx-auto p-4">

    {{-- Titre --}}
    {{-- Changement de text-gray-800 dark:text-white à text-white --}}
    <h1 class="text-3xl font-bold mb-6 text-white">
        <i class="fas fa-users text-blue-600 mr-3"></i>
        Joueurs
    </h1>

    {{-- Conteneur des Filtres (Base : bg-gray-800) --}}
    {{-- Anciennement bg-white dark:bg-gray-800 --}}
    <div class="bg-gray-800 rounded-lg shadow-xl p-4 mb-6">
        <form method="GET" action="{{ route('players.index') }}" class="grid md:grid-cols-5 gap-4 items-end">
            
            {{-- 🟢 FILTRE PAR ÉQUIPE 🟢 --}}
            <div>
                {{-- Label : text-gray-400 --}}
                <label for="team_id" class="block text-sm font-medium mb-2 text-gray-400">Équipe</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white --}}
                <select name="team_id" id="team_id" class="w-full border border-gray-600 rounded-lg px-3 py-2 bg-gray-700 text-white">
                    <option value="">Toutes les équipes</option>
                    @foreach($teams as $team)
                        <option 
                            value="{{ $team->id }}" 
                            {{ request('team_id') == $team->id ? 'selected' : '' }}
                        >
                            {{ $team->name }} ({{ $team->university?->short_name }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- 🟢 FILTRE PAR POSTE 🟢 --}}
            <div>
                {{-- Label : text-gray-400 --}}
                <label for="position" class="block text-sm font-medium mb-2 text-gray-400">Poste</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white --}}
                <select name="position" id="position" class="w-full border border-gray-600 rounded-lg px-3 py-2 bg-gray-700 text-white">
                    <option value="">Tous les postes</option>
                    @foreach(['goalkeeper', 'defender', 'midfielder', 'forward'] as $p_key)
                        <option 
                            value="{{ $p_key }}" 
                            {{ request('position') == $p_key ? 'selected' : '' }}
                        >
                            {{ __('positions.' . $p_key) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- TRIER PAR --}}
            <div>
                {{-- Label : text-gray-400 --}}
                <label for="sort" class="block text-sm font-medium mb-2 text-gray-400">Trier par</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white --}}
                <select name="sort" id="sort" class="w-full border border-gray-600 rounded-lg px-3 py-2 bg-gray-700 text-white">
                    <option value="last_name" {{ request('sort', 'last_name') == 'last_name' ? 'selected' : '' }}>Nom (A-Z)</option>
                    <option value="goals" {{ request('sort') == 'goals' ? 'selected' : '' }}>Buts marqués</option>
                    <option value="assists" {{ request('sort') == 'assists' ? 'selected' : '' }}>Passes Décisives</option>
                </select>
            </div>

            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filtrer
                </button>
                {{-- Bouton Réinitialiser adapté du modèle Matchs --}}
                <a href="{{ route('players.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    {{-- Grille des Joueurs --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($players as $player)
            {{-- Carte du Joueur (Base : bg-gray-800) --}}
            {{-- Anciennement bg-white dark:bg-gray-800 --}}
            <a href="{{ route('players.show', $player) }}" class="bg-gray-800 rounded-lg shadow-xl hover:shadow-2xl transition group overflow-hidden border border-gray-700">
                <div class="p-4 flex items-center gap-4">
                    {{-- AFFICHAGE DE LA PHOTO --}}
                    {{-- Le fond gris de la photo est conservé --}}
                    <div class="w-16 h-16 bg-gray-200 rounded-full flex-shrink-0 overflow-hidden">
                        <img 
                            src="{{ $player->photo_url }}" 
                            alt="{{ $player->full_name }}"
                            class="w-full h-full object-cover"
                        >
                    </div>
                    
                    <div>
                        {{-- Nom du joueur : text-white --}}
                        {{-- Anciennement text-gray-800 dark:text-white --}}
                        <h3 class="font-bold text-white group-hover:text-blue-600">{{ $player->full_name }}</h3>
                        {{-- Équipe/Université : text-gray-400 --}}
                        {{-- Anciennement text-gray-500 dark:text-gray-400 --}}
                        <p class="text-sm text-gray-400">{{ $player->team?->university?->short_name ?? 'N/A' }}</p>
                        
                        <div class="mt-1">
                            @php
                                $badges = [
                                    // Adapté pour un fond sombre constant
                                    'goalkeeper' => 'bg-yellow-700 text-yellow-100',
                                    'defender' => 'bg-blue-700 text-blue-100',
                                    'midfielder' => 'bg-green-700 text-green-100',
                                    'forward' => 'bg-red-700 text-red-100', 
                                ];
                                
                                $position_key = $player->position;
                                // Utilisation de la classe de badge sombre direct
                                $badge_class = $badges[$position_key] ?? 'bg-gray-600 text-gray-200';
                            @endphp
                            <span class="text-xs px-2 py-0.5 rounded {{ $badge_class }}">
                                {{-- Traduire la clé anglaise pour l'affichage --}}
                                {{ __('positions.' . $position_key) }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            {{-- Message "Aucun joueur" (Base : bg-gray-800) --}}
            {{-- Anciennement bg-gray-50 dark:bg-gray-800 --}}
            <div class="md:col-span-4 p-4 text-center text-gray-400 bg-gray-800 rounded-lg border border-gray-700">
                <i class="fas fa-users-slash text-6xl text-gray-600 mb-4"></i>
                <p class="text-lg">Aucun joueur ne correspond aux critères de recherche.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6 text-white">
        {{ $players->links() }}
    </div>
</div>
@endsection