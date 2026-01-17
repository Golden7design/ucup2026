@extends('layouts.admin')

@section('header', 'Modifier : ' . $team->name)

@section('content')
{{-- Conteneur du formulaire : bg-gray-800, border-gray-700 --}}
<div class="max-w-2xl mx-auto bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-700">
    <form action="{{ route('admin.teams.update', $team) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            
            {{-- Champ Université --}}
            <div>
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Université (Maison mère)</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <select name="university_id" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('university_id') border-red-500 @enderror" required>
                    <option value="">Sélectionner une université</option>
                    @foreach($universities as $university)
                        <option value="{{ $university->id }}" {{ old('university_id', $team->university_id) == $university->id ? 'selected' : '' }}>
                            {{ $university->name }} ({{ $university->short_name }})
                        </option>
                    @endforeach
                </select>
                @error('university_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Champ Nom de l'Équipe --}}
            <div>
                <label class="block text-sm font-medium text-white mb-1">Nom de l'Équipe</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="text" name="name" value="{{ old('name', $team->name) }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Champ Entraîneur (Coach) --}}
            <div>
                <label class="block text-sm font-medium text-white mb-1">Nom de l'Entraîneur</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input 
                    type="text" 
                    name="coach" 
                    value="{{ old('coach', $team->coach) }}" 
                    class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('coach') border-red-500 @enderror" 
                    placeholder="Nom de l'entraîneur" 
                    required
                >
                @error('coach')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Champ Catégorie --}}
            <div>
                <label class="block text-sm font-medium text-white mb-1">Catégorie</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <select name="category" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('category') border-red-500 @enderror" required>
                    <option value="" disabled {{ old('category', $team->category) == '' ? 'selected' : '' }}>-- Sélectionner une catégorie --</option>
                    <option value="Senior" {{ old('category', $team->category) == 'Senior' ? 'selected' : '' }}>Senior</option>
                    <option value="Junior" {{ old('category', $team->category) == 'Junior' ? 'selected' : '' }}>Junior</option>
                </select>
                @error('category')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Champ Année --}}
            <div>
                <label class="block text-sm font-medium text-white mb-1">Année</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input 
                    type="number" 
                    name="year" 
                    value="{{ old('year', $team->year) }}"
                    class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('year') border-red-500 @enderror" 
                    required 
                    placeholder="Ex: 2024" 
                    min="2020" 
                    max="2100"
                >
                @error('year')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Champ Slogan --}}
            <div>
                <label class="block text-sm font-medium text-white mb-1">Slogan</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="text" name="slogan" value="{{ old('slogan', $team->slogan) }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('slogan') border-red-500 @enderror">
                @error('slogan')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Champ Logo --}}
            <div>
                <label class="block text-sm font-medium text-white mb-1">Logo / Blason de l'Équipe</label>
                @if($team->logo)
                    <div class="mb-2">
                        {{-- Image : border-gray-600 --}}
                        <img src="{{ asset('storage/' . $team->logo) }}" class="h-16 w-16 object-contain border border-gray-600 rounded p-1">
                    </div>
                @endif
                {{-- File Input : bg-gray-700, border-gray-600, text-white, file:bg-blue-600 --}}
                <input 
                    type="file" 
                    name="logo" 
                    class="w-full border border-gray-600 rounded-lg p-2 text-sm text-white bg-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('logo') border-red-500 @enderror"
                >
                @error('logo')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Champ Détails --}}
            <div>
                <label class="block text-sm font-medium text-white mb-1">Détails</label>
                {{-- Textarea : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <textarea name="details" rows="3" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('details') border-red-500 @enderror">{{ old('details', $team->details) }}</textarea>
                @error('details')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            {{-- Bouton Annuler : bg-gray-700, text-white, hover:bg-gray-600 --}}
            <a href="{{ route('admin.teams.index') }}" class="bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">Annuler</a>
            {{-- Bouton Mettre à jour : bg-blue-600, hover:bg-blue-500 --}}
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-500 transition font-semibold">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection