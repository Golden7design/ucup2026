@extends('layouts.app')

@section('title', 'Classement Groupe ' . $groupName)

@section('content')
<div class="container mx-auto py-4">
    {{-- Titre : text-white, border-gray-700 --}}
    <div class="flex items-center justify-between mb-8 border-b border-gray-700 pb-2">
        <h1 class="text-4xl font-extrabold text-white">
            <i class="fas fa-layer-group text-blue-500 mr-3"></i>
            Classement du Groupe {{ $groupName }}
        </h1>
        {{-- Lien de retour : text-blue-400 --}}
        <a href="{{ route('standings.index') }}" class="text-blue-400 hover:text-blue-500 font-medium flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux Groupes
        </a>
    </div>

    @if($standings->isEmpty())
        {{-- Message d'alerte adapté au mode sombre --}}
        <div class="bg-yellow-900 border-l-4 border-yellow-500 text-yellow-200 p-4 rounded-md" role="alert">
            <p>Aucun match terminé pour générer ce classement.</p>
        </div>
    @else
        {{-- Conteneur du classement : bg-gray-800, shadow-xl, border-gray-700 --}}
        <div class="bg-gray-800 p-6 rounded-xl shadow-xl border border-gray-700">
            @include('frontend.standings._table', ['standings' => $standings])
        </div>
    @endif
</div>
@endsection