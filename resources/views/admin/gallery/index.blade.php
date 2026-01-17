@extends('layouts.admin') {{-- Adapter à votre layout Admin --}}

@section('content')
{{-- Conteneur principal : bg-transparent (s'il est géré par le layout), texte en blanc --}}
<div class="container mx-auto p-4 text-white"> 
    <h2 class="text-3xl font-semibold mb-6">🖼️ Gestion de la Galerie Multimédia</h2>

    @if (session('success'))
        {{-- Message de succès : bg-green-900, border-green-700, text-green-300 --}}
        <div class="bg-green-900 border border-green-700 text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.gallery.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
            Ajouter un Média
        </a>
    </div>

    @if ($items->isEmpty())
        {{-- Texte si galerie vide : text-gray-400 --}}
        <p class="text-gray-400">Aucun élément dans la galerie pour l'instant.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($items as $item)
                {{-- Élément de la galerie : bg-gray-800, shadow-lg, border-gray-700 --}}
                <div class="bg-gray-800 shadow-lg rounded-lg p-4 flex flex-col justify-between border border-gray-700">
                    <div>
                        {{-- Affichage de l'aperçu (images et vidéos) --}}
                        @if ($item->media_type === 'image')
                            <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title }}" class="w-full h-32 object-cover rounded mb-3 border border-gray-700">
                        @elseif ($item->media_type === 'video')
                            <video controls class="w-full h-32 object-cover rounded mb-3 border border-gray-700">
                                <source src="{{ asset('storage/' . $item->file_path) }}" type="video/{{ pathinfo($item->file_path, PATHINFO_EXTENSION) }}">
                            </video>
                        @endif

                        {{-- Titre : text-white --}}
                        <p class="text-lg font-bold text-white">{{ $item->title ?? 'Sans Titre' }}</p>
                        {{-- Métadonnées : text-gray-400 --}}
                        <p class="text-sm text-gray-400">Type: {{ ucfirst($item->media_type) }}</p>
                        <p class="text-sm text-gray-400">Ordre: {{ $item->sort_order }}</p>
                    </div>

                    <div class="mt-4">
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">
                            @csrf
                            @method('DELETE')
                            {{-- Bouton Supprimer : text-red-500, hover:text-red-400 --}}
                            <button type="submit" class="text-red-500 hover:text-red-400 text-sm">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection