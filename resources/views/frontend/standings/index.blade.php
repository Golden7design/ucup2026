@extends('layouts.app')

@section('title', 'Classements - U-Cup Tournament')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Titre principal : text-white --}}
    <h1 class="text-3xl font-bold mb-8 text-white">
        <i class="fas fa-trophy text-yellow-500 mr-3"></i>
        Classements du Tournoi
    </h1>

    @if($standingsByGroup->isEmpty())
        {{-- Message d'alerte adapté au mode sombre --}}
        <div class="bg-yellow-900 border border-yellow-500 text-yellow-200 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Attention!</strong>
            <span class="block sm:inline">Aucun match terminé pour générer un classement.</span>
        </div>
    @else

        {{-- 1. CLASSEMENT GÉNÉRAL --}}
        {{-- Conteneur : bg-gray-800, border-gray-700 --}}
        <div class="bg-gray-800 p-6 rounded-lg shadow-xl mb-10 border border-gray-700">
            {{-- Titre : text-indigo-400, border-gray-700 --}}
            <h2 class="text-2xl font-extrabold mb-4 border-b border-gray-700 pb-2 text-indigo-400">🏆 Classement Général Global</h2>
            
            @include('frontend.standings._table', ['standings' => $generalStandings, 'isGeneral' => true]) 
        </div>
        
        {{-- Séparateur --}}
        <hr class="my-8 border-gray-700">

        {{-- 2. CLASSEMENTS PAR GROUPES --}}
        {{-- Titre : text-white --}}
        <h2 class="text-2xl font-extrabold mb-6 text-white">Classements par Groupes</h2>
        <div class="grid lg:grid-cols-2 xl:grid-cols-3 gap-8">
            @foreach($standingsByGroup as $groupName => $groupStandings)
                {{-- Conteneur de groupe : bg-gray-800, border-gray-700 --}}
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-700">
                    {{-- Titre de groupe : text-blue-400 --}}
                    <h3 class="text-xl font-bold mb-4 text-blue-400">Groupe {{ $groupName }}</h3>
                    @include('frontend.standings._table', ['standings' => $groupStandings, 'isGeneral' => false])
                </div>
            @endforeach
        </div>

    @endif
</div>
@endsection