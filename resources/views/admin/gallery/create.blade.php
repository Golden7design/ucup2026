@extends('layouts.admin') {{-- Adapter à votre layout Admin --}}

@section('content')
<div class="container mx-auto p-4 text-white">
    <h2 class="text-3xl font-semibold mb-6">➕ Ajouter un Nouvel Élément à la Galerie</h2>

    {{-- Lien Retour : text-blue-400 (plus clair pour le sombre) --}}
    <a href="{{ route('admin.gallery.index') }}" class="text-blue-400 hover:underline mb-4 inline-block">← Retour à la Galerie</a>

    {{-- Conteneur du formulaire : bg-gray-800, border-gray-700 --}}
    <div class="bg-gray-800 p-6 rounded-lg shadow-md border border-gray-700">
        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Fichier Média --}}
            <div class="mb-4">
                {{-- Label : text-white --}}
                <label for="media_file" class="block text-white font-bold mb-2">Fichier Média (Image ou Vidéo)</label>
                {{-- Input : bg-gray-700, border-gray-600, text-white --}}
                <input type="file" name="media_file" id="media_file" required class="w-full border p-2 rounded bg-gray-700 border-gray-600 text-white @error('media_file') border-red-500 @enderror">
                @error('media_file')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
                {{-- Message d'aide : text-gray-400 --}}
                <p class="text-xs text-gray-400 mt-1">Formats acceptés : JPG, PNG, GIF, MP4, MOV, AVI. Max 50MB.</p>
            </div>

            {{-- Titre --}}
            <div class="mb-4">
                <label for="title" class="block text-white font-bold mb-2">Titre (optionnel)</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full border p-2 rounded bg-gray-700 border-gray-600 text-white @error('title') border-red-500 @enderror">
                @error('title')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label for="description" class="block text-white font-bold mb-2">Description (optionnel)</label>
                <textarea name="description" id="description" rows="3" class="w-full border p-2 rounded bg-gray-700 border-gray-600 text-white @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- Ordre de tri --}}
            <div class="mb-4">
                <label for="sort_order" class="block text-white font-bold mb-2">Ordre d'affichage (0 pour le premier)</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="w-full border p-2 rounded bg-gray-700 border-gray-600 text-white @error('sort_order') border-red-500 @enderror">
                @error('sort_order')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bouton Enregistrer --}}
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500">
                Enregistrer le Média
            </button>
        </form>
    </div>
</div>
@endsection