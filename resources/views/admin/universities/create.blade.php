@extends('layouts.admin')

@section('header', 'Ajouter une Université')

@section('content')
{{-- Conteneur du formulaire : bg-gray-800, border-gray-700 --}}
<div class="max-w-2xl mx-auto bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-700">
    <form action="{{ route('admin.universities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 gap-6">
            <div>
                {{-- Label : text-white --}}
                <label class="block text-sm font-medium text-white mb-1">Nom de l'Université</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('name') border-red-500 @enderror" required placeholder="Ex: Université de Kinshasa">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-1">Sigle</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="text" name="short_name" value="{{ old('short_name') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('short_name') border-red-500 @enderror" required placeholder="Ex: UNIKIN">
                @error('short_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-1">Couleurs</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <input type="text" name="colors" value="{{ old('colors') }}" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('colors') border-red-500 @enderror" placeholder="Ex: Bleu et Blanc">
                @error('colors')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-1">Description</label>
                {{-- Textarea : bg-gray-700, border-gray-600, text-white, focus:ring-blue-400 --}}
                <textarea name="description" class="w-full border-gray-600 rounded-lg shadow-sm focus:ring-blue-400 focus:border-blue-400 bg-gray-700 text-white @error('description') border-red-500 @enderror" placeholder="Description de l'université">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-white mb-1">Logo</label>
                {{-- File Input : bg-gray-700, border-gray-600, text-white, file:bg-blue-600 --}}
                <input 
                    type="file" 
                    name="logo" 
                    class="w-full border border-gray-600 rounded-lg p-2 text-sm text-white bg-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-400 @error('logo') border-red-500 @enderror"
                    accept="image/*"
                >
                 @error('logo')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            {{-- Bouton Annuler : bg-gray-700, text-white, hover:bg-gray-600 --}}
            <a href="{{ route('admin.universities.index') }}" class="bg-gray-700 text-white px-5 py-2 rounded-lg hover:bg-gray-600 transition font-semibold">Annuler</a>
            {{-- Bouton Enregistrer : bg-blue-600, hover:bg-blue-500 --}}
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-500 transition font-semibold">Enregistrer</button>
        </div>
    </form>
</div>
@endsection