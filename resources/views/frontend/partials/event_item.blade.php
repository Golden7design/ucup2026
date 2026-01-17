{{-- Assurez-vous que $event et $match sont passés à cette partiale --}}
@php
    $teamName = $event->team->university->short_name ?? $event->team->name;
    $playerName = $event->player->full_name ?? ($event->player->first_name . ' ' . $event->player->last_name);
@endphp

{{-- Minute : text-white --}}
<span class="minute-display w-12 text-right font-bold text-lg text-white">{{ $event->minute }}'</span>
{{-- Séparateur : text-gray-500 --}}
<span class="mx-4 text-gray-500">|</span>
<div class="flex-1">
    @if(in_array($event->event_type, ['goal', 'penalty_goal']))
        {{-- BUT : text-green-400 (clair) --}}
        ⚽ <span class="font-semibold text-green-400">BUT!</span> 
        {{-- Nom du joueur : text-white --}}
        <span class="text-white">{{ $playerName }}</span> ({{ $teamName }})
        @if($event->assistPlayer)
            {{-- Assistance : text-gray-400 --}}
            <span class="text-sm text-gray-400 block ml-6">Assisté par {{ $event->assistPlayer->full_name ?? ($event->assistPlayer->first_name . ' ' . $event->assistPlayer->last_name) }}</span>
        @endif
    @elseif($event->event_type === 'own_goal')
        {{-- C.S.C. : text-yellow-400 --}}
        🥅 <span class="font-semibold text-yellow-400">BUT C.S.C.</span> de 
        <span class="text-white">{{ $playerName }}</span> ({{ $teamName }})
    @elseif($event->event_type === 'yellow_card')
        {{-- Carton Jaune : text-white --}}
        🟨 Carton Jaune : <span class="text-white">{{ $playerName }}</span> ({{ $teamName }})
    @elseif($event->event_type === 'red_card')
        {{-- Carton Rouge : text-white --}}
        🟥 Carton Rouge : <span class="text-white">{{ $playerName }}</span> ({{ $teamName }})
    @elseif($event->event_type === 'substitution_in')
        {{-- Remplacement : text-blue-400 --}}
        🔄 <span class="text-blue-400">Remplacement :</span> 
        {{-- Entrée/Sortie : text-white / text-red-400 --}}
        <span class="font-semibold text-white">Entrée</span> : {{ $playerName }} ({{ $teamName }})
        @if($event->outPlayer)
            / <span class="font-semibold text-red-400">Sortie</span> : {{ $event->outPlayer->full_name ?? ($event->outPlayer->first_name . ' ' . $event->outPlayer->last_name) }}
        @endif
    @endif
</div>