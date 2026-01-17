@extends('layouts.admin')

{{-- Section 'header' utilisée pour le titre dans l'en-tête du layout --}}
@section('header', 'Modifier le Match')

{{-- OUVRE LA SECTION 'content' pour le corps principal du layout --}}
@section('content')

{{-- Conteneur du formulaire : Utilisation des classes sombres directes --}}
<div class="max-w-4xl mx-auto bg-gray-800 rounded-lg shadow p-6 border border-gray-700">
    <form action="{{ route('admin.matches.update', $match) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Équipe Domicile</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white --}}
                <select name="home_team_id" class="w-full border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400" required>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('home_team_id', $match->home_team_id) == $team->id ? 'selected' : '' }}>
                            {{ $team->name }} ({{ $team->university->short_name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Équipe Extérieure</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white --}}
                <select name="away_team_id" class="w-full border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400" required>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('away_team_id', $match->away_team_id) == $team->id ? 'selected' : '' }}>
                            {{ $team->name }} ({{ $team->university->short_name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-span-1">
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Date et Heure du Match</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white --}}
                <input type="datetime-local" name="match_date" value="{{ \Carbon\Carbon::parse(old('match_date', $match->match_date))->format('Y-m-d\TH:i') }}" class="w-full border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400" required>
            </div>

            <div class="col-span-1">
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Lieu (Stade)</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white --}}
                <input type="text" name="venue" value="{{ old('venue', $match->venue) }}" class="w-full border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400" placeholder="Ex: Stade de Kintélé">
            </div>

            <div class="col-span-1">
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Groupe (Optionnel)</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white --}}
                <select name="group" id="group" class="w-full border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400">
                    <option value="">-- Aucun groupe --</option>
                    @foreach($groups as $g)
                        <option value="{{ $g }}" {{ old('group', $match->group) == $g ? 'selected' : '' }}>Groupe {{ $g }}</option>
                    @endforeach
                </select>
                @error('group')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-span-1">
                {{-- Label : text-white --}}
                <label for="match_type" class="block text-sm font-medium text-white mb-1">Type de Match</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white --}}
                <select name="match_type" id="match_type" class="w-full border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400" required>
                    <option value="tournament" {{ old('match_type', $match->match_type) == 'tournament' ? 'selected' : '' }}>Tournoi (Compte pour le Classement)</option>
                    <option value="friendly" {{ old('match_type', $match->match_type) == 'friendly' ? 'selected' : '' }}>Amical (Hors Classement)</option>
                </select>
                @error('match_type')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="col-span-2">
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Statut</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white --}}
                <select name="status" class="w-full border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400" required>
                    <option value="scheduled" {{ old('status', $match->status) === 'scheduled' ? 'selected' : '' }}>Planifié</option>
                    <option value="live" {{ old('status', $match->status) === 'live' ? 'selected' : '' }}>En Direct</option>
                    <option value="halftime" {{ old('status', $match->status) === 'halftime' ? 'selected' : '' }}>Mi-temps</option>
                    <option value="finished" {{ old('status', $match->status) === 'finished' ? 'selected' : '' }}>Terminé</option>
                    <option value="postponed" {{ old('status', $match->status) === 'postponed' ? 'selected' : '' }}>Reporté</option>
                    <option value="cancelled" {{ old('status', $match->status) === 'cancelled' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            
        </div>

        {{-- Titre de section : text-white, border-gray-700 --}}
        <h3 class="text-lg font-semibold mt-6 mb-4 border-b pb-1 text-white border-gray-700">Scores du Match</h3>
        <div class="grid grid-cols-2 gap-6">
            <div>
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Score Domicile ({{ $match->homeTeam->name }})</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white --}}
                <input type="number" name="home_score" value="{{ old('home_score', $match->home_score ?? 0) }}" min="0" class="w-full border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400">
            </div>
            <div>
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Score Extérieur ({{ $match->awayTeam->name }})</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white --}}
                <input type="number" name="away_score" value="{{ old('away_score', $match->away_score ?? 0) }}" min="0" class="w-full border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400">
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-3">
            {{-- Bouton Annuler : bg-gray-700, text-white, hover:bg-gray-600 --}}
            <a href="{{ route('admin.matches.index') }}" class="bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">Annuler</a>
            {{-- Bouton Mettre à Jour (couleur vive conservée) --}}
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Mettre à Jour le Match</button>
        </div>
    </form>
</div>

{{-- FERME LA SECTION 'content' --}}
@endsection