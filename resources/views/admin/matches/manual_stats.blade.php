@extends('layouts.admin')

@section('title', 'Statistiques Manuelles - ' . $match->homeTeam->university->name . ' vs ' . $match->awayTeam->university->name)

@section('content')
<div class="container mx-auto p-4 sm:p-6 lg:p-8">
    <div class="bg-gray-800 shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-chart-bar mr-2"></i>
                Statistiques Manuelles
            </h1>
            <a href="{{ route('admin.matches.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded transition">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </div>

        <div class="bg-gray-700 rounded-lg p-4 mb-6">
            <h2 class="text-xl font-semibold text-white mb-4">Match Information</h2>
            <div class="flex justify-between items-center">
                <div class="text-center">
                    <div class="font-bold text-lg">{{ $match->homeTeam->university->name }}</div>
                    <div class="text-gray-300">{{ $match->homeTeam->name }}</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-400">
                        {{ $match->home_score }} - {{ $match->away_score }}
                    </div>
                    <div class="text-sm text-gray-400">
                        {{ $match->match_date->format('d/m/Y H:i') }}
                    </div>
                </div>
                <div class="text-center">
                    <div class="font-bold text-lg">{{ $match->awayTeam->university->name }}</div>
                    <div class="text-gray-300">{{ $match->awayTeam->name }}</div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.matches.stats.update', $match) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Possession (%)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="home_possession" class="block text-sm font-medium text-gray-300 mb-1">
                            {{ $match->homeTeam->university->short_name }} Possession
                        </label>
                        <input type="number" id="home_possession" name="home_possession" 
                               value="{{ old('home_possession', $match->home_possession ?? 50) }}" 
                               class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="0" max="100">
                    </div>
                    <div>
                        <label for="away_possession" class="block text-sm font-medium text-gray-300 mb-1">
                            {{ $match->awayTeam->university->short_name }} Possession
                        </label>
                        <input type="number" id="away_possession" name="away_possession" 
                               value="{{ old('away_possession', $match->away_possession ?? 50) }}" 
                               class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               min="0" max="100">
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-2">La somme doit faire 100%. Si ce n'est pas le cas, elle sera corrigée automatiquement.</p>
            </div>

            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Statistiques de Match</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-white mb-3">{{ $match->homeTeam->university->short_name }}</h4>
                        <div class="space-y-3">
                            <div>
                                <label for="home_shots" class="block text-sm font-medium text-gray-300 mb-1">Tirs</label>
                                <input type="number" id="home_shots" name="home_shots" 
                                       value="{{ old('home_shots', $match->home_shots ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="home_shots_on_target" class="block text-sm font-medium text-gray-300 mb-1">Tirs cadrés</label>
                                <input type="number" id="home_shots_on_target" name="home_shots_on_target" 
                                       value="{{ old('home_shots_on_target', $match->home_shots_on_target ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="home_corners" class="block text-sm font-medium text-gray-300 mb-1">Corners</label>
                                <input type="number" id="home_corners" name="home_corners" 
                                       value="{{ old('home_corners', $match->home_corners ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="home_fouls" class="block text-sm font-medium text-gray-300 mb-1">Fautes</label>
                                <input type="number" id="home_fouls" name="home_fouls" 
                                       value="{{ old('home_fouls', $match->home_fouls ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-white mb-3">{{ $match->awayTeam->university->short_name }}</h4>
                        <div class="space-y-3">
                            <div>
                                <label for="away_shots" class="block text-sm font-medium text-gray-300 mb-1">Tirs</label>
                                <input type="number" id="away_shots" name="away_shots" 
                                       value="{{ old('away_shots', $match->away_shots ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="away_shots_on_target" class="block text-sm font-medium text-gray-300 mb-1">Tirs cadrés</label>
                                <input type="number" id="away_shots_on_target" name="away_shots_on_target" 
                                       value="{{ old('away_shots_on_target', $match->away_shots_on_target ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="away_corners" class="block text-sm font-medium text-gray-300 mb-1">Corners</label>
                                <input type="number" id="away_corners" name="away_corners" 
                                       value="{{ old('away_corners', $match->away_corners ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="away_fouls" class="block text-sm font-medium text-gray-300 mb-1">Fautes</label>
                                <input type="number" id="away_fouls" name="away_fouls" 
                                       value="{{ old('away_fouls', $match->away_fouls ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Cartons</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-white mb-3">{{ $match->homeTeam->university->short_name }}</h4>
                        <div class="space-y-3">
                            <div>
                                <label for="home_yellow_cards" class="block text-sm font-medium text-gray-300 mb-1">Cartons Jaunes</label>
                                <input type="number" id="home_yellow_cards" name="home_yellow_cards" 
                                       value="{{ old('home_yellow_cards', $match->home_yellow_cards ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="home_red_cards" class="block text-sm font-medium text-gray-300 mb-1">Cartons Rouges</label>
                                <input type="number" id="home_red_cards" name="home_red_cards" 
                                       value="{{ old('home_red_cards', $match->home_red_cards ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="home_offsides" class="block text-sm font-medium text-gray-300 mb-1">Hors-jeu</label>
                                <input type="number" id="home_offsides" name="home_offsides" 
                                       value="{{ old('home_offsides', $match->home_offsides ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-medium text-white mb-3">{{ $match->awayTeam->university->short_name }}</h4>
                        <div class="space-y-3">
                            <div>
                                <label for="away_yellow_cards" class="block text-sm font-medium text-gray-300 mb-1">Cartons Jaunes</label>
                                <input type="number" id="away_yellow_cards" name="away_yellow_cards" 
                                       value="{{ old('away_yellow_cards', $match->away_yellow_cards ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="away_red_cards" class="block text-sm font-medium text-gray-300 mb-1">Cartons Rouges</label>
                                <input type="number" id="away_red_cards" name="away_red_cards" 
                                       value="{{ old('away_red_cards', $match->away_red_cards ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                            <div>
                                <label for="away_offsides" class="block text-sm font-medium text-gray-300 mb-1">Hors-jeu</label>
                                <input type="number" id="away_offsides" name="away_offsides" 
                                       value="{{ old('away_offsides', $match->away_offsides ?? 0) }}" 
                                       class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white" min="0">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-700 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-white mb-4">Notes et Rapport</h3>
                <div class="space-y-4">
                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-300 mb-1">Notes de l'Admin</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3" 
                                  class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Notes internes sur le match...">{{ old('admin_notes', $match->admin_notes) }}</textarea>
                    </div>
                    <div>
                        <label for="match_report" class="block text-sm font-medium text-gray-300 mb-1">Rapport de Match</label>
                        <textarea id="match_report" name="match_report" rows="5" 
                                  class="w-full bg-gray-600 border border-gray-500 rounded-md py-2 px-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Rapport détaillé du match...">{{ old('match_report', $match->match_report) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded transition duration-150 ease-in-out">
                    <i class="fas fa-save mr-2"></i> Sauvegarder les Statistiques
                </button>
                <a href="{{ route('admin.matches.stats.show', $match) }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded transition duration-150 ease-in-out">
                    <i class="fas fa-eye mr-2"></i> Voir le Résumé
                </a>
            </div>
        </form>
    </div>
</div>
@endsection