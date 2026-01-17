@extends('layouts.admin')

@section('header', 'Planifier un Nouveau Match')

@section('content')
{{-- Conteneur du formulaire : bg-gray-800, border-gray-700 --}}
<div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow p-6 border border-gray-700">
    <form action="{{ route('admin.matches.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                {{-- Label : text-white --}}
                <label for="home_team_id" class="block text-sm font-medium text-white mb-1">Équipe à Domicile</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <select name="home_team_id" id="home_team_id" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white" required>
                    <option value="">-- Sélectionner une équipe --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('home_team_id') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }} ({{ $team->university->short_name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('home_team_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="away_team_id" class="block text-sm font-medium text-white mb-1">Équipe à l'Extérieur</label>
                <select name="away_team_id" id="away_team_id" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white" required>
                    <option value="">-- Sélectionner une équipe --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('away_team_id') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }} ({{ $team->university->short_name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('away_team_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="match_date" class="block text-sm font-medium text-white mb-1">Date et Heure du Match</label>
                {{-- Input datetime-local --}}
                <input type="datetime-local" name="match_date" id="match_date" value="{{ old('match_date') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white" required>
                @error('match_date')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="venue" class="block text-sm font-medium text-white mb-1">Lieu du Match (Stade)</label>
                <input type="text" name="venue" id="venue" value="{{ old('venue') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white" placeholder="Ex: Stade de l'Unité">
                @error('venue')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="group" class="block text-sm font-medium text-white mb-1">Groupe (Optionnel)</label>
                <select name="group" id="group" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white">
                    <option value="">-- Aucun groupe --</option>
                    @foreach($groups as $g)
                        <option value="{{ $g }}" {{ old('group') == $g ? 'selected' : '' }}>Groupe {{ $g }}</option>
                    @endforeach
                </select>
                @error('group')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-1">
                <label for="match_type" class="block text-sm font-medium text-white mb-1">Type de Match</label>
                <select name="match_type" id="match_type" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white" required>
                    <option value="tournament" {{ old('match_type', 'tournament') == 'tournament' ? 'selected' : '' }}>Tournoi (Compte pour le Classement)</option>
                    <option value="friendly" {{ old('match_type') == 'friendly' ? 'selected' : '' }}>Amical (Hors Classement)</option>
                </select>
                @error('match_type')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-3">
            {{-- Bouton Annuler : bg-gray-700, text-white, hover:bg-gray-600 --}}
            <a href="{{ route('admin.matches.index') }}" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Annuler</a>
            {{-- Bouton Planifier (couleur vive conservée) --}}
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition">Planifier le Match</button>
        </div>
    </form>
</div>
@endsection