{{-- Fichier : resources/views/frontend/partials/lineup_list.blade.php --}}
{{-- 
    Variables attendues :
    - $lineups : Collection des objets Lineup (avec la relation 'player' chargée)
    - $isAway : booléen pour justifier le texte (facultatif, par défaut false)
--}}
@php
    $isAway = $isAway ?? false;
    $textAlign = $isAway ? 'text-right' : 'text-left';
@endphp

{{-- Texte par défaut en blanc --}}
<div class="space-y-1 text-white">
    @forelse($lineups as $lineupItem)
        @php 
            $player = $lineupItem->player; 
            if (!$player || !$player->jersey_number) continue; 

            $fullName = $player->full_name ?? ($player->first_name . ' ' . $player->last_name);
            $photoUrl = $player->photo_path ? asset('storage/' . $player->photo_path) : null;
        @endphp
        
        {{-- Ligne du joueur : hover:bg-gray-700 --}}
        <div class="flex items-center p-1 hover:bg-gray-700 rounded {{ $textAlign }}">
            
            {{-- Affichage Équipe Domicile --}}
            @if (!$isAway)
                <div class="w-10 flex-shrink-0">
                    @if ($photoUrl)
                        <img src="{{ $photoUrl }}" 
                            alt="{{ $fullName }}" 
                            {{-- Bordure en gris foncé --}}
                            class="h-8 w-8 object-cover rounded-full border border-gray-600">
                    @else
                        {{-- Icône en gris clair --}}
                        <i class="fas fa-user-circle text-gray-400 text-2xl"></i>
                    @endif
                </div>
                <div class="flex-1 ml-2 truncate">
                    {{-- Numéro : text-gray-400 --}}
                    <span class="font-bold text-gray-400 mr-2">#{{ $player->jersey_number }}</span>
                    {{-- Nom : text-white (par défaut dans le conteneur) --}}
                    <span>{{ $fullName }}</span>
                </div>
            
            {{-- Affichage Équipe Extérieure --}}
            @else
                <div class="flex-1 mr-2 truncate">
                    <span>{{ $fullName }}</span>
                    <span class="font-bold text-gray-400 ml-2">#{{ $player->jersey_number }}</span>
                </div>
                <div class="w-10 flex-shrink-0 ml-auto text-right">
                    @if ($photoUrl)
                        <img src="{{ $photoUrl }}" 
                            alt="{{ $fullName }}" 
                            class="h-8 w-8 object-cover rounded-full border border-gray-600 ml-auto">
                    @else
                        <i class="fas fa-user-circle text-gray-400 text-2xl ml-auto"></i>
                    @endif
                </div>
            @endif
        </div>
    @empty
        <p class="text-center text-gray-500 p-2">Composition non disponible.</p>
    @endforelse
</div>