{{-- Fichier : resources/views/frontend/gallery/index.blade.php --}}

@extends('layouts.app')

{{-- Ajout du style pour cacher la modal Alpine au chargement (si non défini dans app.blade.php) --}}
@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="container mx-auto p-4" x-data="{}"> 
    {{-- Titre principal en blanc --}}
    <h1 class="text-4xl font-bold mb-8 text-white">📸 Galerie Multimédia du Tournoi</h1>

    @if ($items->isEmpty())
        {{-- Message d'alerte adapté au mode sombre --}}
        <div class="bg-yellow-900 border-l-4 border-yellow-500 text-yellow-200 p-4 rounded-md" role="alert">
            <p class="font-bold">Galerie en cours de construction</p>
            <p>Aucun média (photos ou vidéos) n'a encore été ajouté à la galerie. Revenez bientôt !</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($items as $item)
                
                {{-- Conteneur cliquable : bg-gray-800, border-gray-700, shadow-xl --}}
                <div class="bg-gray-800 shadow-xl rounded-lg overflow-hidden cursor-pointer hover:shadow-2xl transition duration-300 border border-gray-700">
                    
                    @php
                        $mediaUrl = asset('storage/' . $item->file_path);
                    @endphp

                    {{-- Affichage de l'Image/Vidéo (pas de changement ici, seulement le conteneur) --}}
                    
                    @if ($item->media_type === 'image')
                        <div class="h-64 relative"
                             @click="$dispatch('open-modal', { url: '{{ $mediaUrl }}', type: 'image' })">
                            <img src="{{ $mediaUrl }}" 
                                 alt="{{ $item->title }}" 
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 opacity-0 hover:opacity-100 transition duration-300">
                                <i class="fas fa-search-plus text-white text-3xl"></i>
                            </div>
                        </div>
                    @elseif ($item->media_type === 'video')
                        <div class="h-64 relative bg-black"
                             @click="$dispatch('open-modal', { url: '{{ $mediaUrl }}', type: 'video' })">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-play-circle text-white text-5xl opacity-80 hover:opacity-100 transition"></i>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Détails du Média --}}
                    <div class="p-4">
                        {{-- Titres en blanc --}}
                        <h3 class="text-xl font-semibold text-white">{{ $item->title ?? 'Média sans titre' }}</h3>
                        @if ($item->description)
                            {{-- Description en gris clair --}}
                            <p class="text-gray-400 mt-2 text-sm">{{ $item->description }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>


{{-- Le composant Lightbox Modal est majoritairement noir, donc il ne nécessite pas de changements majeurs, 
     mais j'assure que le fond reste noir transparent. --}}
<div x-data="{ open: false, mediaUrl: '', mediaType: '' }" 
     @open-modal.window="open = true; mediaUrl = $event.detail.url; mediaType = $event.detail.type"
     @keydown.escape.window="open = false"
     x-cloak> 
    
    <div x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-90"
         style="display: none;">
         
        <div class="flex items-center justify-center min-h-screen p-4">
            
            <div @click.away="open = false"
                 class="relative w-full max-w-4xl max-h-screen mx-auto rounded-lg overflow-hidden shadow-2xl">
                
                <button @click="open = false" 
                        class="absolute top-4 right-4 text-white hover:text-gray-300 transition z-50">
                    <i class="fas fa-times text-3xl"></i>
                </button>

                <div class="relative w-full h-full p-0">
                    <template x-if="mediaType === 'image'">
                        <img :src="mediaUrl" alt="Média en taille réelle" class="w-full h-auto object-contain max-h-[90vh]">
                    </template>

                    <template x-if="mediaType === 'video'">
                        <div class="relative pb-[56.25%] h-0 overflow-hidden bg-black max-h-[90vh]">
                            <video controls autoplay 
                                   :src="mediaUrl" 
                                   class="absolute top-0 left-0 w-full h-full">
                                Votre navigateur ne supporte pas la lecture de cette vidéo.
                            </video>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection