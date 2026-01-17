@extends('layouts.app')

@section('title', 'Équipes - U-Cup Tournament')

@section('content')
<div class="mb-8">
    {{-- Titre principal en blanc --}}
    <h1 class="text-3xl font-bold mb-6 text-white">
        <i class="fas fa-users text-blue-400 mr-3"></i>
        Équipes Participantes
    </h1>

    <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($teams as $team)
        {{-- Carte de l'équipe : bg-gray-800, border-gray-700, hover:shadow-2xl --}}
        <a href="{{ route('teams.show', $team) }}" 
           class="bg-gray-800 rounded-lg shadow-xl hover:shadow-2xl transition transform hover:-translate-y-1 group border border-gray-700">
            <div class="p-6 text-center">
                <div class="h-32 flex items-center justify-center mb-4">
                    @if($team->university->logo)
                        <img src="{{ asset('storage/' . $team->university->logo) }}" 
                             alt="{{ $team->university->name }}" 
                             class="max-h-full max-w-full object-contain group-hover:scale-110 transition">
                    @else
                        {{-- Icône par défaut en gris foncé --}}
                        <i class="fas fa-shield-alt text-6xl text-gray-500 group-hover:text-blue-400 transition"></i>
                    @endif
                </div>
                
                {{-- Nom : text-white --}}
                <h2 class="text-xl font-bold text-white mb-1">{{ $team->university->short_name }}</h2>
                {{-- Détails : text-gray-400 --}}
                <p class="text-sm text-gray-400 mb-4">{{ $team->university->name }}</p>
                
                {{-- Statistiques rapides --}}
                {{-- Séparateur : border-gray-700 --}}
                <div class="border-t border-gray-700 pt-4 grid grid-cols-2 gap-2 text-sm">
                    <div>
                        {{-- Labels en gris clair --}}
                        <span class="block text-gray-400">Coach</span>
                        {{-- Valeurs en blanc --}}
                        <span class="font-semibold text-white">{{ $team->coach ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-gray-400">Joueurs</span>
                        <span class="font-semibold text-white">{{ $team->players_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
            
            {{-- Bas de carte : bg-gray-700 --}}
            <div class="bg-gray-700 px-6 py-3 text-center rounded-b-lg">
                {{-- Lien : text-blue-400 --}}
                <span class="text-blue-400 font-semibold text-sm group-hover:text-blue-500">
                    Voir l'effectif <i class="fas fa-arrow-right ml-1"></i>
                </span>
            </div>
        </a>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{-- Les styles de pagination doivent être gérés dans le layout ou le provider Laravel, 
             mais la balise elle-même reste la même. --}}
        {{ $teams->links() }}
    </div>
</div>
@endsection