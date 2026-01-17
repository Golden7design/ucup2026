{{-- 
Ce fichier gère la visualisation des joueurs sur le terrain.
Il reçoit :
- $homeStartersCollection (Collection de Lineup pour l'équipe domicile)
- $awayStartersCollection (Collection de Lineup pour l'équipe extérieur)
- $match (Objet Match)
--}}

@php
    // (Pas de changement dans les coordonnées ou $playerBaseClasses)
    $positionCoordinates = [
        // GARDIEN (GOALKEEPER)
        'G' => ['home' => ['x' => 50, 'y' => 90], 'away' => ['x' => 50, 'y' => 10]],

        // DÉFENSEURS (DEFENDERS - D)
        'DC' => ['home' => ['x' => 50, 'y' => 75], 'away' => ['x' => 50, 'y' => 25]], // Défenseur Central (Fallback/unique)
        'DCD' => ['home' => ['x' => 60, 'y' => 78], 'away' => ['x' => 40, 'y' => 22]], // Central Droit
        'DCG' => ['home' => ['x' => 40, 'y' => 78], 'away' => ['x' => 60, 'y' => 22]], // Central Gauche
        'DD' => ['home' => ['x' => 85, 'y' => 70], 'away' => ['x' => 15, 'y' => 30]], // Latéral Droit
        'DG' => ['home' => ['x' => 15, 'y' => 70], 'away' => ['x' => 85, 'y' => 30]], // Latéral Gauche

        // MILIEUX (MIDFIELDERS - M)
        'MDC' => ['home' => ['x' => 50, 'y' => 60], 'away' => ['x' => 50, 'y' => 40]], // Défensif Central
        'MC' => ['home' => ['x' => 50, 'y' => 50], 'away' => ['x' => 50, 'y' => 50]], // Central (Milieu 50/50)
        'MCD' => ['home' => ['x' => 65, 'y' => 50], 'away' => ['x' => 35, 'y' => 50]], // Central Droit
        'MCG' => ['home' => ['x' => 35, 'y' => 50], 'away' => ['x' => 65, 'y' => 50]], // Central Gauche
        'MD' => ['home' => ['x' => 80, 'y' => 50], 'away' => ['x' => 20, 'y' => 50]], // Milieu Droit
        'MG' => ['home' => ['x' => 20, 'y' => 50], 'away' => ['x' => 80, 'y' => 50]], // Milieu Gauche
        'MOC' => ['home' => ['x' => 50, 'y' => 40], 'away' => ['x' => 50, 'y' => 60]], // Offensif Central

        // ATTAQUANTS (FORWARDS - A/AT)
        'AT' => ['home' => ['x' => 50, 'y' => 20], 'away' => ['x' => 50, 'y' => 80]], // Attaquant (Fallback)
        'AC' => ['home' => ['x' => 50, 'y' => 20], 'away' => ['x' => 50, 'y' => 80]], // Avant Centre
        'AILD' => ['home' => ['x' => 75, 'y' => 25], 'away' => ['x' => 25, 'y' => 75]], // Ailier Droit
        'AILG' => ['home' => ['x' => 25, 'y' => 25], 'away' => ['x' => 75, 'y' => 75]], // Ailier Gauche
    ];

    $playerBaseClasses = 'absolute transform -translate-x-1/2 -translate-y-1/2 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg z-10 transition-all duration-300';
    
    $homeUnmappedCount = 0;
    $awayUnmappedCount = 0;
@endphp

<div class="relative w-full h-[600px] bg-green-700 border-4 border-white overflow-hidden rounded-lg shadow-inner mt-4 mb-8"
     style="background-image: linear-gradient(to bottom, #10b981 0%, #065f46 100%);">
    
    {{-- Lignes du terrain (aucune modification nécessaire, elles sont blanches) --}}
    <div class="absolute inset-0 border-4 border-white opacity-90">
        <div class="absolute top-1/2 left-0 right-0 h-1 bg-white transform -translate-y-1/2"></div>
        <div class="absolute top-1/2 left-1/2 w-32 h-32 border-2 border-white rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute top-1/2 left-1/2 w-2 h-2 bg-white rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute top-0 left-1/2 w-3/4 h-24 border-b-2 border-l-2 border-r-2 border-white transform -translate-x-1/2"></div>
        <div class="absolute top-16 left-1/2 w-1 h-1 bg-white rounded-full transform -translate-x-1/2"></div>
        <div class="absolute bottom-0 left-1/2 w-3/4 h-24 border-t-2 border-l-2 border-r-2 border-white transform -translate-x-1/2"></div>
        <div class="absolute bottom-16 left-1/2 w-1 h-1 bg-white rounded-full transform -translate-x-1/2"></div>
        <div class="absolute top-0 left-1/2 w-1/4 h-1 border-t-4 border-red-500 transform -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-1/2 w-1/4 h-1 border-t-4 border-blue-500 transform -translate-x-1/2 translate-y-1/2"></div>
    </div>

    {{-- PLACEMENT DES JOUEURS : ÉQUIPE DOMICILE (HOME) --}}
    @foreach($homeStartersCollection as $lineup)
        @php
            $positionCode = strtoupper($lineup->position);
            $coords = $positionCoordinates[$positionCode]['home'] ?? null;
            
            if (!$coords) {
                $homeUnmappedCount++;
                $left = 5 + (($homeUnmappedCount - 1) * (90 / 10)) . '%'; 
                $top = '70%'; 
                $positionCode = 'N/A';
            } else {
                $left = $coords['x'] . '%';
                $top = $coords['y'] . '%';
            }
            
            $playerNumber = $lineup->player->jersey_number ?? '?';
        @endphp

        {{-- Les couleurs d'équipe n'ont pas besoin d'être ajustées, elles sont spécifiques (bleu/rouge) --}}
        <div class="{{ $playerBaseClasses }} bg-blue-600 hover:bg-blue-400" style="left: {{ $left }}; top: {{ $top }};" 
             title="{{ $lineup->player->full_name ?? ($lineup->player->first_name . ' ' . $lineup->player->last_name) }} ({{ $positionCode }})">
            {{ $playerNumber }}
        </div>
    @endforeach

    {{-- PLACEMENT DES JOUEURS : ÉQUIPE EXTÉRIEUR (AWAY) --}}
    @foreach($awayStartersCollection as $lineup)
        @php
            $positionCode = strtoupper($lineup->position);
            $coords = $positionCoordinates[$positionCode]['away'] ?? null;
            
            if (!$coords) {
                $awayUnmappedCount++;
                $left = 5 + (($awayUnmappedCount - 1) * (90 / 10)) . '%';
                $top = '30%'; 
                $positionCode = 'N/A';
            } else {
                $left = $coords['x'] . '%';
                $top = $coords['y'] . '%';
            }
            
            $playerNumber = $lineup->player->jersey_number ?? '?';
        @endphp

        <div class="{{ $playerBaseClasses }} bg-red-600 hover:bg-red-400" style="left: {{ $left }}; top: {{ $top }};"
             title="{{ $lineup->player->full_name ?? ($lineup->player->first_name . ' ' . $lineup->player->last_name) }} ({{ $positionCode }})">
            {{ $playerNumber }}
        </div>
    @endforeach
    
</div>

{{-- Message d'info-bulle : text-gray-400 --}}
<p class="text-sm text-gray-400 mt-2">
    * Les joueurs avec la mention **(N/A)** dans l'info-bulle n'avaient pas de poste spécifique mappé et sont positionnés sur une ligne de secours.
</p>