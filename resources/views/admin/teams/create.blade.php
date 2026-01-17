@extends('layouts.admin')

@section('header', 'Ajouter une Équipe')

@section('content')
{{-- Conteneur du formulaire : bg-gray-800, border-gray-700 --}}
<div class="max-w-2xl mx-auto bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-700">
    <form action="{{ route('admin.teams.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <div>
                {{-- Label : text-white --}}
                <label class="block text-sm font-semibold text-white mb-1">Université</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <select name="university_id" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('university_id') border-red-500 @enderror" required>
                    <option value="">-- Sélectionner une université --</option>
                    @foreach($universities as $university)
                        <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>{{ $university->name }}</option>
                    @endforeach
                </select>
                @error('university_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1">Nom de l'Équipe</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('name') border-red-500 @enderror" required placeholder="Ex: FC UMNG">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1">Nom de l'Entraîneur</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input 
                    type="text" 
                    name="coach" 
                    value="{{ old('coach') }}"
                    class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('coach') border-red-500 @enderror" 
                    placeholder="Nom de l'entraîneur"
                    required
                >
                @error('coach')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1">Catégorie</label>
                {{-- Select : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <select name="category" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('category') border-red-500 @enderror" required>
                    <option value="" disabled {{ old('category') == '' ? 'selected' : '' }}>-- Sélectionner une catégorie --</option>
                    <option value="Senior" {{ old('category') == 'Senior' ? 'selected' : '' }}>Senior</option>
                    <option value="Junior" {{ old('category') == 'Junior' ? 'selected' : '' }}>Junior</option>
                </select>
                @error('category')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-white mb-1">Année</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input 
                    type="number" 
                    name="year" 
                    value="{{ old('year', date('Y')) }}"
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
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            {{-- Bouton Annuler : bg-gray-700, text-white, hover:bg-gray-600 --}}
            <a href="{{ route('admin.teams.index') }}" class="bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">Annuler</a>
            {{-- Bouton Enregistrer : bg-blue-600, hover:bg-blue-500 --}}
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-500 transition font-semibold">Enregistrer</button>
        </div>
    </form>
</div>
@endsection