@extends('layouts.admin')

@section('header', 'Live Center : Gestion des Matchs en Direct')

@section('content')
<div class="max-w-7xl mx-auto text-white">
    <h3 class="text-2xl font-bold mb-6">Matchs En Cours et à Venir</h3>

    @if($matches->isEmpty())
        {{-- Alerte : bg-yellow-900, border-yellow-700, text-yellow-300 --}}
        <div class="bg-yellow-900 border-l-4 border-yellow-700 text-yellow-300 p-4" role="alert">
            <p>Aucun match planifié ou en direct pour le moment.</p>
        </div>
    @else
        {{-- Conteneur de la liste : bg-gray-800, shadow-lg, border-gray-700 --}}
        <div class="bg-gray-800 shadow-lg overflow-hidden sm:rounded-lg border border-gray-700">
            {{-- Séparateur : divide-gray-700 --}}
            <ul class="divide-y divide-gray-700">
                @foreach($matches as $match)
                    <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-700 transition duration-150">
                        <div class="flex-1 min-w-0">
                            {{-- Nom du match : text-white --}}
                            <p class="text-sm font-medium text-white truncate">
                                ⚽ {{ $match->homeTeam->university->short_name }} vs {{ $match->awayTeam->university->short_name }}
                            </p>
                            {{-- Métadonnées : text-gray-400 --}}
                            <p class="text-sm text-gray-400">
                                {{ $match->match_date->format('d/m/Y H:i') }} | Statut : 
                                <span class="font-semibold @if($match->status === 'live') text-red-400 @elseif($match->status === 'finished') text-green-400 @else text-yellow-400 @endif">
                                    {{ ucfirst($match->status) }}
                                </span>
                            </p>
                            {{-- Score : text-gray-500 --}}
                            <p class="text-xs text-gray-500">
                                Score : {{ $match->home_score }} - {{ $match->away_score }}
                            </p>
                        </div>
                        
                        <div>
                            {{-- Bouton Gérer le Live : bg-red-600/700 (couleur vive conservée) --}}
                            <a href="{{ route('admin.live.show', $match) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-400 focus:ring-offset-gray-800">
                                Gérer le Live
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <div class="mt-4">
            {{-- Assurez-vous que la pagination utilise des classes compatibles avec le mode sombre --}}
            {{ $matches->links() }}
        </div>
    @endif
</div>
@endsection