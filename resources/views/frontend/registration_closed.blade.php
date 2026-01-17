@extends('layouts.registration')

@section('title', 'Inscription Fermée')

@section('content')
<div class="max-w-xl mx-auto px-4 text-center">
    <div class="bg-gray-800 shadow-2xl rounded-xl p-10 border border-gray-700">
        <i class="fas fa-calendar-times text-6xl text-red-500 mb-6"></i>
        <h1 class="text-3xl font-bold text-white mb-4">Période d'Inscription Terminée</h1>
        <p class="text-gray-400 mb-6">
            Les inscriptions pour les joueurs du tournoi U-Cup 2026 sont désormais closes depuis le {{ Carbon\Carbon::parse(\App\Http\Controllers\Frontend\PlayerRegistrationController::REGISTRATION_DEADLINE)->isoFormat('LLLL') }}.
        </p>
        <p class="text-gray-300 font-medium">
            Pour toute question, veuillez contacter le Bureau Départemental des Étudiants (BDDE) de l'ESTAM.
        </p>
        <a href="{{ route('home') }}" class="mt-6 inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md transition duration-150">
            Retour à la page du Tournoi
        </a>
    </div>
</div>
@endsection