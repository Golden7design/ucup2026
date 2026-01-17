@extends('layouts.admin')

@section('header', 'Composition dÉquipe')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Messages de session adaptés au thème sombre --}}
    @if(session('success'))
        <div class="bg-green-800 border-l-4 border-green-500 text-white p-4 mb-6 rounded-md shadow-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-800 border-l-4 border-red-500 text-white p-4 mb-6 rounded-md shadow-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- En-tête avec informations du match --}}
    <div class="bg-gray-800 rounded-xl shadow-lg p-6 mb-6 border border-gray-700">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">
                    Composition d'Équipe : {{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}
                </h1>
                <div class="flex items-center space-x-4 text-sm text-gray-400">
                    <span><i class="fas fa-calendar-alt mr-1"></i> {{ $match->match_date->format('d/m/Y H:i') }}</span>
                    <span><i class="fas fa-map-marker-alt mr-1"></i> {{ $match->venue ?? 'N/A' }}</span>
                    <span class="font-semibold {{ match_status_class_tailwind($match->status) }}">
                        {{ ucfirst($match->status) }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.matches.edit', $match) }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg transition font-semibold">
                    <i class="fas fa-edit mr-1"></i> Modifier Match
                </a>
                @if($match->status === 'scheduled')
                <a href="{{ route('admin.live.show', $match) }}" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg transition font-semibold">
                    <i class="fas fa-play mr-1"></i> Passer en Direct
                </a>
                @endif
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.matches.store_lineup', $match) }}">
        @csrf
        
        @php
            // Définition des options de postes par catégorie
            $positionGroups = [
                'Gardiens' => ['G' => 'G (Gardien)'],
                'Défenseurs' => ['DC' => 'DC (Défenseur Central)', 'DCD' => 'DCD (Central Droit)', 'DCG' => 'DCG (Central Gauche)', 'DD' => 'DD (Latéral Droit)', 'DG' => 'DG (Latéral Gauche)'],
                'Milieux' => ['MDC' => 'MDC (Défensif)', 'MC' => 'MC (Central)', 'MCD' => 'MCD (Central Droit)', 'MCG' => 'MCG (Central Gauche)', 'MD' => 'MD (Latéral Droit)', 'MG' => 'MG (Latéral Gauche)', 'MOC' => 'MOC (Offensif)'],
                'Attaquants' => ['AT' => 'AT (Attaquant)', 'AC' => 'AC (Avant Centre)', 'AILD' => 'AILD (Ailier Droit)', 'AILG' => 'AILG (Ailier Gauche)'],
            ];

            // Définition des options disponibles pour chaque ligne (1 à 11)
            $linePositionOptions = [];
            $linePositionOptions[1] = $positionGroups['Gardiens']; 
            $linePositionOptions[2] = $positionGroups['Défenseurs']; 
            $linePositionOptions[3] = $positionGroups['Défenseurs'];
            $linePositionOptions[4] = $positionGroups['Défenseurs'] + $positionGroups['Milieux']; 
            $linePositionOptions[5] = $positionGroups['Défenseurs'] + $positionGroups['Milieux']; 
            $linePositionOptions[6] = $positionGroups['Milieux'] + $positionGroups['Attaquants'];
            $linePositionOptions[7] = $positionGroups['Milieux'] + $positionGroups['Attaquants'];
            $linePositionOptions[8] = $positionGroups['Milieux'] + $positionGroups['Attaquants'];
            $linePositionOptions[9] = $positionGroups['Attaquants'] + $positionGroups['Milieux'];
            $linePositionOptions[10] = $positionGroups['Attaquants'] + $positionGroups['Milieux'];
            $linePositionOptions[11] = $positionGroups['Attaquants'] + $positionGroups['Milieux'];

            // Fonction pour générer le HTML des options de poste
            function generatePositionOptions($line, $savedPosition, $positionGroups, $linePositionOptions) {
                $optionsHtml = '<option value="">-- Poste --</option>';
                $options = $linePositionOptions[$line] ?? array_merge(...array_values($positionGroups)); 
                
                if ($savedPosition && !array_key_exists($savedPosition, $options)) {
                    $allOptions = array_merge(...array_values($positionGroups));
                    $displayName = $allOptions[$savedPosition] ?? $savedPosition;
                    $optionsHtml .= '<option value="' . $savedPosition . '" selected>' . $displayName . ' (Sauvegardé)</option>';
                }

                foreach ($positionGroups as $groupName => $groupOptions) {
                    $hasOptions = false;
                    foreach ($groupOptions as $code => $name) {
                        if (array_key_exists($code, $options)) {
                            if (!$hasOptions) {
                                $optionsHtml .= '<optgroup label="' . $groupName . '">';
                                $hasOptions = true;
                            }
                            $selected = ($code == $savedPosition) ? 'selected' : '';
                            $optionsHtml .= '<option value="' . $code . '" ' . $selected . '>' . $name . '</option>';
                        }
                    }
                    if ($hasOptions) {
                        $optionsHtml .= '</optgroup>';
                    }
                }

                return $optionsHtml;
            }
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- ================================================= --}}
            {{-- ZONE DE SÉLECTION : ÉQUIPE À DOMICILE --}}
            {{-- ================================================= --}}
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 border-t-4 border-blue-600">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-blue-400">
                        {{ $match->homeTeam->name }} (Domicile)
                    </h2>
                    @if($match->homeTeam->university && $match->homeTeam->university->logo)
                    <img src="{{ asset('storage/' . $match->homeTeam->university->logo) }}" alt="Logo" class="h-10 w-10 object-contain rounded-full border border-gray-600">
                    @endif
                </div>

                {{-- Champ Entraîneur --}}
                <div class="mb-4">
                    <label for="home_coach" class="block text-sm font-medium text-gray-300">Nom de l'Entraîneur</label>
                    <input type="text" name="home_coach" id="home_coach" 
                            value="{{ old('home_coach', $match->home_coach ?? '') }}" 
                            class="mt-1 block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg shadow-sm p-2 focus:ring-blue-400 focus:border-blue-400">
                </div>
                
                {{-- Champ Formation --}}
                <div class="mb-4">
                    <label for="home_formation" class="block text-sm font-medium text-gray-300">Formation (Ex: 4-4-2)</label>
                    <input type="text" name="home_formation" id="home_formation" 
                            value="{{ old('home_formation', $match->home_formation ?? '') }}" 
                            class="mt-1 block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg shadow-sm p-2 focus:ring-blue-400 focus:border-blue-400"
                            maxlength="10" placeholder="Ex: 4-4-2">
                </div>

                <hr class="my-6 border-gray-700">
                
                {{-- Sélecteur du Onze de Départ --}}
                <h3 class="text-lg font-medium mb-3 text-white">Onze de Départ (11 joueurs)</h3>
                <p class="text-sm text-gray-400 mb-4">Sélectionnez le joueur et son poste spécifique dans la formation.</p>

                <div class="space-y-3">
                    @for ($i = 0; $i < 11; $i++) 
                        @php
                            $line = $i + 1; // Le numéro de ligne (1 à 11)
                            $selectedPlayerId = isset($homeStartersIds[$i]) ? $homeStartersIds[$i] : null;
                            $savedPosition = isset($homeStarterPositions[$i]) ? $homeStarterPositions[$i] : ($line == 1 ? 'G' : ''); // Défaut 'G' pour le N°1
                        @endphp
                        <div class="flex items-center space-x-2">
                            <span class="w-8 text-right font-medium text-gray-400">{{ $line }}.</span>
                            
                            {{-- SÉLECTEUR DU JOUEUR (2/3 de la largeur) --}}
                            <select name="home_starters[]" class="block w-2/3 border-gray-600 bg-gray-700 text-gray-200 rounded-lg p-2 focus:ring-blue-400 focus:border-blue-400" required>
                                <option value="">-- Sélectionner un titulaire --</option>
                                @foreach ($homePlayers as $player)
                                    <option value="{{ $player->id }}"
                                            {{ $selectedPlayerId == $player->id ? 'selected' : '' }}>
                                        {{ $player->jersey_number ? $player->jersey_number . ' - ' : '' }}
                                        {{ $player->full_name ?? $player->first_name . ' ' . $player->last_name }} ({{ ucfirst($player->position) }})
                                    </option>
                                @endforeach
                            </select>
                            
                            {{-- SÉLECTEUR DU POSTE DANS LA FORMATION (1/3 de la largeur) --}}
                            <select name="home_starter_positions[]" class="w-1/3 border-gray-600 bg-gray-700 text-gray-200 rounded-lg p-2 text-sm focus:ring-blue-400 focus:border-blue-400" required>
                                {!! generatePositionOptions($line, $savedPosition, $positionGroups, $linePositionOptions) !!}
                            </select>
                        </div>
                    @endfor
                </div>

                <hr class="my-6 border-gray-700">
                
                {{-- Sélecteur du Banc de Remplaçants --}}
                <h3 class="text-lg font-medium mb-3 text-white">Banc de Remplaçants</h3>
                <p class="text-sm text-gray-400 mb-4">Sélectionnez les joueurs qui seront sur le banc (Excluez les 11 titulaires).</p>
                
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($homePlayers as $player)
                        @php
                            $isChecked = isset($homeSubsIds) && in_array($player->id, $homeSubsIds);
                        @endphp
                        <div class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition">
                            <input type="checkbox" name="home_substitutes[]" value="{{ $player->id }}" id="home_sub_{{ $player->id }}" 
                                        class="mr-2 rounded text-blue-600 focus:ring-blue-400"
                                        {{ $isChecked ? 'checked' : '' }}>
                            <label for="home_sub_{{ $player->id }}" class="text-sm text-gray-200">
                                {{ $player->jersey_number ? $player->jersey_number . ' - ' : '' }}
                                {{ $player->full_name ?? $player->first_name . ' ' . $player->last_name }} ({{ ucfirst($player->position) }})
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            
            {{-- ================================================= --}}
            {{-- ZONE DE SÉLECTION : ÉQUIPE À L'EXTÉRIEUR --}}
            {{-- ================================================= --}}
            <div class="bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-700 border-t-4 border-red-600">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-red-400">
                        {{ $match->awayTeam->name }} (Extérieur)
                    </h2>
                    @if($match->awayTeam->university && $match->awayTeam->university->logo)
                    <img src="{{ asset('storage/' . $match->awayTeam->university->logo) }}" alt="Logo" class="h-10 w-10 object-contain rounded-full border border-gray-600">
                    @endif
                </div>

                {{-- Champ Entraîneur --}}
                <div class="mb-4">
                    <label for="away_coach" class="block text-sm font-medium text-gray-300">Nom de l'Entraîneur</label>
                    <input type="text" name="away_coach" id="away_coach" 
                            value="{{ old('away_coach', $match->away_coach ?? '') }}" 
                            class="mt-1 block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg shadow-sm p-2 focus:ring-red-400 focus:border-red-400">
                </div>
                
                {{-- Champ Formation --}}
                <div class="mb-4">
                    <label for="away_formation" class="block text-sm font-medium text-gray-300">Formation (Ex: 4-3-3)</label>
                    <input type="text" name="away_formation" id="away_formation" 
                            value="{{ old('away_formation', $match->away_formation ?? '') }}" 
                            class="mt-1 block w-full border-gray-600 bg-gray-700 text-gray-200 rounded-lg shadow-sm p-2 focus:ring-red-400 focus:border-red-400"
                            maxlength="10" placeholder="Ex: 4-3-3">
                </div>

                <hr class="my-6 border-gray-700">
                
                {{-- Sélecteur du Onze de Départ --}}
                <h3 class="text-lg font-medium mb-3 text-white">Onze de Départ (11 joueurs)</h3>
                <p class="text-sm text-gray-400 mb-4">Sélectionnez le joueur et son poste spécifique dans la formation.</p>

                <div class="space-y-3">
                    @for ($i = 0; $i < 11; $i++)
                        @php
                            $line = $i + 1; // Le numéro de ligne (1 à 11)
                            $selectedPlayerId = isset($awayStartersIds[$i]) ? $awayStartersIds[$i] : null;
                            $savedPosition = isset($awayStarterPositions[$i]) ? $awayStarterPositions[$i] : ($line == 1 ? 'G' : ''); // Défaut 'G' pour le N°1
                        @endphp
                        <div class="flex items-center space-x-2">
                            <span class="w-8 text-right font-medium text-gray-400">{{ $line }}.</span>
                            
                            {{-- SÉLECTEUR DU JOUEUR (2/3 de la largeur) --}}
                            <select name="away_starters[]" class="block w-2/3 border-gray-600 bg-gray-700 text-gray-200 rounded-lg p-2 focus:ring-red-400 focus:border-red-400" required>
                                <option value="">-- Sélectionner un titulaire --</option>
                                @foreach ($awayPlayers as $player)
                                    <option value="{{ $player->id }}"
                                            {{ $selectedPlayerId == $player->id ? 'selected' : '' }}>
                                        {{ $player->jersey_number ? $player->jersey_number . ' - ' : '' }}
                                        {{ $player->full_name ?? $player->first_name . ' ' . $player->last_name }} ({{ ucfirst($player->position) }})
                                    </option>
                                @endforeach
                            </select>
                            
                            {{-- SÉLECTEUR DU POSTE DANS LA FORMATION (1/3 de la largeur) --}}
                            <select name="away_starter_positions[]" class="w-1/3 border-gray-600 bg-gray-700 text-gray-200 rounded-lg p-2 text-sm focus:ring-red-400 focus:border-red-400" required>
                                {!! generatePositionOptions($line, $savedPosition, $positionGroups, $linePositionOptions) !!}
                            </select>
                        </div>
                    @endfor
                </div>

                <hr class="my-6 border-gray-700">
                
                {{-- Sélecteur du Banc de Remplaçants --}}
                <h3 class="text-lg font-medium mb-3 text-white">Banc de Remplaçants</h3>
                <p class="text-sm text-gray-400 mb-4">Sélectionnez les joueurs qui seront sur le banc (Excluez les 11 titulaires).</p>
                
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($awayPlayers as $player)
                        @php
                            $isChecked = isset($awaySubsIds) && in_array($player->id, $awaySubsIds);
                        @endphp
                        <div class="flex items-center p-2 rounded-lg hover:bg-gray-700 transition">
                            <input type="checkbox" name="away_substitutes[]" value="{{ $player->id }}" id="away_sub_{{ $player->id }}" 
                                        class="mr-2 rounded text-red-600 focus:ring-red-400"
                                        {{ $isChecked ? 'checked' : '' }}>
                            <label for="away_sub_{{ $player->id }}" class="text-sm text-gray-200">
                                {{ $player->jersey_number ? $player->jersey_number . ' - ' : '' }}
                                {{ $player->full_name ?? $player->first_name . ' ' . $player->last_name }} ({{ ucfirst($player->position) }})
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-gray-700">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                <i class="fas fa-save mr-2"></i> Enregistrer la Composition
            </button>
        </div>
    </form>
</div>
@endsection

{{-- Fonction utilitaire pour le statut (COULEURS DARK DÉFINIES) --}}
@php
function match_status_class_tailwind($status) {
    return [
        'scheduled' => 'bg-blue-800 text-blue-100',
        'live' => 'bg-red-800 text-red-100 animate-pulse',
        'halftime' => 'bg-yellow-800 text-yellow-100',
        'finished' => 'bg-green-800 text-green-100',
        'postponed' => 'bg-gray-700 text-gray-200',
        'cancelled' => 'bg-gray-500 text-gray-100',
    ][$status] ?? 'bg-indigo-800 text-indigo-100';
}
@endphp