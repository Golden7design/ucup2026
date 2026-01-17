{{--
    Ce fichier est inclus dans resources/views/frontend/matches/show.blade.php
    Il reçoit la variable : $events (Collection de MatchEvent triée, provenant de $match->matchEvents)
--}}

@php
    // Définition des couleurs/icônes pour les différents types d'événements
    $eventIcons = [
        'goal' => ['icon' => 'fas fa-futbol', 'color' => 'text-green-600'],
        'yellow_card' => ['icon' => 'fas fa-id-card', 'color' => 'text-yellow-500'],
        'red_card' => ['icon' => 'fas fa-id-card', 'color' => 'text-red-600'],
        'substitution' => ['icon' => 'fas fa-exchange-alt', 'color' => 'text-blue-500'],
        'penalty_goal' => ['icon' => 'fas fa-bullseye', 'color' => 'text-green-800'],
        'own_goal' => ['icon' => 'fas fa-skull-crossbones', 'color' => 'text-red-400'],
    ];

    // Le tri par minute décroissante est déjà fait dans la vue appelante
    // $events est une collection de MatchEvent
@endphp

@if ($events->isEmpty())
    {{-- Affiche ce message si la vue est chargée directement par l'API sans événements --}}
    <p class="text-gray-600 text-center py-4">Aucun événement enregistré pour ce match.</p>
@else

    {{-- Conteneur principal de la chronologie --}}
    <div class="space-y-4">
        @foreach ($events as $event)
            {{-- Détermine si l'événement est pour l'équipe à domicile ou à l'extérieur --}}
            @php
                // Assurez-vous que la relation 'team' est chargée ou accessible via l'événement
                $teamSide = ($event->team_id === $match->home_team_id) ? 'home' : 'away';
                $isHome = ($teamSide === 'home');
                $iconData = $eventIcons[$event->event_type] ?? ['icon' => 'fas fa-info-circle', 'color' => 'text-gray-500'];
                
                // Libellé de l'événement
                $playerName = optional($event->player)->name ?? 'Joueur Inconnu';
                $description = '';

                switch ($event->event_type) {
                    case 'goal':
                        $description = "But de {$playerName}";
                        break;
                    case 'penalty_goal':
                        $description = "But sur Penalty de {$playerName}";
                        break;
                    case 'own_goal':
                        $description = "But Contre Son Camp (CSC) de {$playerName}";
                        break;
                    case 'yellow_card':
                        $description = "Carton Jaune pour {$playerName}";
                        break;
                    case 'red_card':
                        $description = "Carton Rouge pour {$playerName}";
                        break;
                    case 'substitution':
                        // Nécessite la relation 'playerOut' et 'playerIn' si elles existent sur MatchEvent
                        $playerOutName = optional($event->playerOut)->name ?? 'Joueur sortant';
                        $playerInName = optional($event->playerIn)->name ?? 'Joueur entrant';
                        $description = "Substitution : {$playerOutName} sort, {$playerInName} entre";
                        break;
                    default:
                        $description = "Événement : {$event->event_type}";
                }
            @endphp

            <div class="flex items-center w-full">
                
                {{-- Côté Équipe à Domicile --}}
                <div class="w-5/12 text-right pr-4">
                    @if ($isHome)
                        <div class="font-semibold text-sm leading-tight text-gray-800">
                            {{ $description }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ optional($event->team->university)->short_name }}
                        </div>
                    @endif
                </div>

                {{-- Ligne Centrale (Minute et Icône) --}}
                <div class="w-2/12 flex flex-col items-center">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center border-2 
                                {{ $iconData['color'] }} bg-white z-10 shadow-md">
                        <i class="{{ $iconData['icon'] }} text-xs"></i>
                    </div>
                    <div class="h-10 border-r border-gray-300 transform -translate-y-1"></div>
                    <span class="text-xs font-bold text-gray-600 -mt-3 z-20 bg-white px-1 rounded-full border border-gray-300">
                        {{ $event->minute }}'
                    </span>
                </div>

                {{-- Côté Équipe à l'Extérieur --}}
                <div class="w-5/12 text-left pl-4">
                    @if (!$isHome)
                        <div class="font-semibold text-sm leading-tight text-gray-800">
                            {{ $description }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ optional($event->team->university)->short_name }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

@endif