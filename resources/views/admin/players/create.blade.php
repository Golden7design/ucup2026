@extends('layouts.admin')

@section('header', 'Ajouter un Nouveau Joueur')

@section('content')
{{-- Conteneur du formulaire : bg-gray-800, border-gray-700 --}}
<div class="max-w-3xl mx-auto bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-700">
    {{-- 🚨 CORRECTION 1 : Ajout de l'enctype pour permettre l'upload de fichiers --}}
    <form action="{{ route('admin.players.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                {{-- Label : text-white --}}
                <label for="team_id" class="block text-sm font-semibold text-white mb-1">Équipe (Université)</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <select name="team_id" id="team_id" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('team_id') border-red-500 @enderror" required>
                    <option value="">-- Sélectionnez une équipe --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }} ({{ $team->university->short_name ?? 'Université inconnue' }})
                        </option>
                    @endforeach
                </select>
                @error('team_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="last_name" class="block text-sm font-semibold text-white mb-1">Nom de Famille</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('last_name') border-red-500 @enderror" required>
                @error('last_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="first_name" class="block text-sm font-semibold text-white mb-1">Prénom(s)</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('first_name') border-red-500 @enderror" required>
                @error('first_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="jersey_number" class="block text-sm font-semibold text-white mb-1">Numéro de Maillot</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="number" name="jersey_number" id="jersey_number" value="{{ old('jersey_number') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('jersey_number') border-red-500 @enderror" placeholder="Ex: 10" min="1" max="99">
                @error('jersey_number')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="position" class="block text-sm font-semibold text-white mb-1">Poste</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <select name="position" id="position" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('position') border-red-500 @enderror" required>
                    <option value="">-- Sélectionnez un poste --</option>
                    @php
                        $positions = ['Gardien', 'Défenseur', 'Milieu', 'Attaquant'];
                    @endphp
                    @foreach($positions as $pos)
                        <option value="{{ $pos }}" {{ old('position') == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                    @endforeach
                </select>
                @error('position')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="birth_date" class="block text-sm font-semibold text-white mb-1">Date de Naissance (Optionnel)</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('birth_date') border-red-500 @enderror">
                @error('birth_date')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="height" class="block text-sm font-semibold text-white mb-1">Taille (cm, Optionnel)</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="number" name="height" id="height" value="{{ old('height') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('height') border-red-500 @enderror" placeholder="Ex: 180">
                @error('height')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label for="photo" class="block text-sm font-semibold text-white mb-1">Photo du Joueur (Optionnel)</label>
                {{-- File Input : bg-gray-700, border-gray-600, text-white, file:bg-blue-600 --}}
                <input 
                    type="file" 
                    name="photo" 
                    id="photo"
                    class="w-full border border-gray-600 rounded-lg p-2 text-sm text-white bg-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('photo') border-red-500 @enderror"
                >
                <p class="mt-1 text-xs text-gray-400">Formats acceptés : JPG, PNG (Max 2MB)</p>
                @error('photo')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
        </div>

        <div class="mt-8 flex justify-end space-x-3">
            {{-- Bouton Annuler : bg-gray-700, text-white, hover:bg-gray-600 --}}
            <a href="{{ route('admin.players.index') }}" class="bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">Annuler</a>
            {{-- Bouton Enregistrer : bg-blue-600, hover:bg-blue-500 --}}
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-500 transition font-semibold shadow-md">Enregistrer le Joueur</button>
        </div>
    </form>
</div>
@endsection