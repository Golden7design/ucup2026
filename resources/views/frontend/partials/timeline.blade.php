@php
    $homeTeamId = $match->home_team_id;
    $awayTeamId = $match->away_team_id;

    // Définir les icônes et classes pour chaque type d'événement
    // Ajustement des couleurs pour être plus visibles/cohérentes sur un fond sombre
    $eventIcons = [
        'goal' => ['icon' => '⚽', 'color' => 'text-green-400', 'label' => 'BUT!'],
        'penalty_goal' => ['icon' => '🥅', 'color' => 'text-green-400', 'label' => 'BUT (Pen.)!'],
        'own_goal' => ['icon' => '🤕', 'color' => 'text-red-400', 'label' => 'C.S.C.'],
        'yellow_card' => ['icon' => '🟨', 'color' => 'text-yellow-400', 'label' => 'Carton Jaune'],
        'red_card' => ['icon' => '🟥', 'color' => 'text-red-600', 'label' => 'Carton Rouge'],
        'substitution_in' => ['icon' => '⬆️', 'color' => 'text-blue-400', 'label' => 'Entrée'],
        'substitution_out' => ['icon' => '⬇️', 'color' => 'text-blue-400', 'label' => 'Sortie'],
    ];

    // Note: Dans LiveMatchController, nous chargeons : 
    // ->with(['player', 'assistPlayer', 'outPlayer', 'team.university'])
@endphp

<div class="events-list space-y-3">
    @forelse ($events as $event)
        @php
            $isHomeEvent = $event->team_id == $homeTeamId;
            $teamName = $event->team->university->name ?? $event->team->name;
            $player = $event->player;
            $assistPlayer = $event->assistPlayer;
            $outPlayer = $event->outPlayer;
            $iconData = $eventIcons[$event->event_type] ?? ['icon' => '⚙️', 'color' => 'text-gray-500', 'label' => 'Événement'];

            $alignmentClass = $isHomeEvent ? 'flex-row-reverse' : 'flex-row'; 
            $textClass = $isHomeEvent ? 'text-right' : 'text-left';
            $bgColor = $iconData['color'];
            $eventHtmlId = "event-{$event->id}"; 
        @endphp

        {{-- Conteneur de l'événement : bg-gray-800, hover:bg-gray-700 --}}
        <div id="{{ $eventHtmlId }}" class="flex {{ $alignmentClass }} items-start gap-3 p-2 rounded-lg bg-gray-800 transition-all duration-300 hover:bg-gray-700 border border-gray-700">
            
            {{-- Minute : text-white --}}
            <div class="font-bold text-lg w-12 flex-shrink-0 text-white {{ $textClass }}">
                {{ $event->minute }}'
            </div>
            
            <div class="flex-shrink-0">
                {{-- Fond du cercle d'icône : bg-opacity-20 ou bg-opacity-10 pour le mode sombre --}}
                <span class="p-1 rounded-full {{ $bgColor }} bg-opacity-20 text-xl" title="{{ $iconData['label'] }}">
                    {!! $iconData['icon'] !!}
                </span>
            </div>

            <div class="flex-grow {{ $textClass }}">
                
                {{-- Affichage des Buts --}}
                @if (in_array($event->event_type, ['goal', 'penalty_goal', 'own_goal']))
                    <p class="font-semibold {{ $iconData['color'] }}">
                        {{ $iconData['label'] }}! ({{ $teamName }})
                    </p>
                    {{-- Nom du joueur : text-gray-300 --}}
                    <p class="text-sm text-gray-300">
                        {{ $player->full_name ?? ($player->first_name . ' ' . $player->last_name) }}
                    </p>
                    @if ($assistPlayer)
                        {{-- Assistance : text-gray-400 --}}
                        <p class="text-xs text-gray-400 italic">
                            Assisté par {{ $assistPlayer->full_name ?? ($assistPlayer->first_name . ' ' . $assistPlayer->last_name) }}
                        </p>
                    @endif
                
                {{-- Affichage des Cartons --}}
                @elseif (in_array($event->event_type, ['yellow_card', 'red_card']))
                    <p class="font-semibold {{ $iconData['color'] }}">
                        {{ $iconData['label'] }} ({{ $teamName }})
                    </p>
                    <p class="text-sm text-gray-300">
                        {{ $player->full_name ?? ($player->first_name . ' ' . $player->last_name) }}
                    </p>
                
                {{-- Affichage des Substitutions --}}
                @elseif ($event->event_type === 'substitution_in')
                    {{-- Titre : text-blue-400 --}}
                    <p class="font-semibold text-blue-400">
                        Remplacement ({{ $teamName }})
                    </p>
                    {{-- Entrée : text-green-400 --}}
                    <p class="text-sm text-green-400 font-medium">
                        {{ $iconData['icon'] }} {{ $player->full_name ?? ($player->first_name . ' ' . $player->last_name) }} (Entre)
                    </p>
                    @if ($outPlayer)
                        {{-- Sortie : text-red-400 --}}
                        <p class="text-xs text-red-400 font-medium">
                            {{ $eventIcons['substitution_out']['icon'] }} {{ $outPlayer->full_name ?? ($outPlayer->first_name . ' ' . $outPlayer->last_name) }} (Sort)
                        </p>
                    @endif
                @endif
                
                @if ($event->description)
                    {{-- Description : text-gray-500 --}}
                    <p class="text-xs text-gray-500 mt-1">{{ $event->description }}</p>
                @endif
            </div>
        </div>
    @empty
        <p class="text-center text-gray-500 py-4">
            Aucun événement n'a été enregistré pour le moment.
        </p>
    @endforelse
</div>