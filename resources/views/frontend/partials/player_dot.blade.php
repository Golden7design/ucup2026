@php
    // Extraction des données du joueur
    $player = $lineup->player;
    $playerName = $player->full_name ?? ($player->first_name . ' ' . $player->last_name);
    $jerseyNumber = $player->jersey_number ?? '?';
    $positionDisplay = $lineup->position ?? $lineup->lineup_role; // Utiliser le rôle si la position n'est pas détaillée

    // Définition des classes CSS
    // Ces classes CSS doivent être définies ailleurs (ex: app.css) pour le mode sombre.
    // Assurez-vous que les classes .dot-home et .dot-away affichent bien le numéro de maillot en BLANC (text-white).
    $dotClass = $teamType === 'home' ? 'dot-home' : 'dot-away';
@endphp

<div class="player-dot {{ $dotClass }}" 
     style="top: {{ $top }}%; left: {{ $left }}%;"
     title="#{{ $jerseyNumber }} {{ $playerName }} ({{ $positionDisplay }})">
    
    {{ $jerseyNumber }}

    {{-- Tooltip pour plus d'info au survol (version simple avec l'attribut title) --}}
    {{-- 
        *** Ajustements pour le mode sombre ***
        - bg-gray-800 (fond sombre)
        - text-white (texte clair)
    --}}
    <div class="player-tooltip absolute bg-gray-800 text-white p-2 rounded text-xs whitespace-nowrap opacity-0 transition-opacity pointer-events-none top-full mt-2 border border-gray-700 shadow-xl">
        #{{ $jerseyNumber }} {{ $playerName }} ({{ $positionDisplay }})
    </div>
</div>