@extends('layouts.registration')

@section('title', 'Inscription des Joueurs')

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <h1 class="text-3xl font-bold text-white mb-3 text-center">
        <i class="fas fa-user-plus text-green-400 mr-2"></i> Portail d'Inscription Joueur U-Cup 2026
    </h1>
    <p class="text-gray-400 text-center mb-8">
        Formulaire réservé aux responsables d'équipes et aux joueurs. Toutes les données sont enregistrées dans la base de données officielle du tournoi.
        <br>
        <span class="text-yellow-400 font-semibold">Date limite d'inscription : {{ Carbon\Carbon::parse(\App\Http\Controllers\Frontend\PlayerRegistrationController::REGISTRATION_DEADLINE)->isoFormat('LLL') }}</span>
    </p>

    <div class="bg-gray-800 shadow-2xl rounded-xl p-6 md:p-10 border border-gray-700 w-full">
        
        @if(session('success'))
            <div class="bg-green-800/50 border border-green-500 text-green-300 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline"><i class="fas fa-check-circle mr-2"></i> {{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-800/50 border border-red-500 text-red-300 px-4 py-3 rounded relative mb-4">
                <span class="block sm:inline"><i class="fas fa-times-circle mr-2"></i> {{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-800/50 border border-red-500 text-red-300 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Erreur(s) de saisie !</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('player.register.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label for="team_id" class="block text-sm font-medium text-gray-300 mb-1">Équipe (Université) <span class="text-red-500">*</span></label>
                    <select name="team_id" id="team_id" required
                            class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:ring-green-500 focus:border-green-500 @error('team_id') border-red-500 @enderror">
                        <option value="">-- Sélectionnez une équipe --</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                {{ $team->university->name ?? $team->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-300 mb-1">Poste <span class="text-red-500">*</span></label>
                    <select name="position" id="position" required
                            class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:ring-green-500 focus:border-green-500 @error('position') border-red-500 @enderror">
                        <option value="">-- Sélectionnez un poste --</option>
                        @foreach ($posts as $post)
                            <option value="{{ $post }}" {{ old('position') == $post ? 'selected' : '' }}>
                                {{ $post }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-300 mb-1">Nom de Famille <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                           class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:ring-green-500 focus:border-green-500 @error('last_name') border-red-500 @enderror" placeholder="Ex: MOUKOUANGA">
                </div>
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-300 mb-1">Prénom(s) <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                           class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:ring-green-500 focus:border-green-500 @error('first_name') border-red-500 @enderror" placeholder="Ex: Elmish Segara">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="jersey_number" class="block text-sm font-medium text-gray-300 mb-1">Numéro de Maillot <span class="text-red-500">*</span></label>
                    <input type="number" name="jersey_number" id="jersey_number" value="{{ old('jersey_number') }}" required min="1" max="99"
                           class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:ring-green-500 focus:border-green-500 @error('jersey_number') border-red-500 @enderror" placeholder="Ex: 10">
                    @error('jersey_number')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="height" class="block text-sm font-medium text-gray-300 mb-1">Taille (cm, Optionnel)</label>
                    <input type="number" name="height" id="height" value="{{ old('height') }}" min="100" max="250"
                           class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:ring-green-500 focus:border-green-500 @error('height') border-red-500 @enderror" placeholder="Ex: 180">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-300 mb-1">Date de Naissance (Optionnel)</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                           class="w-full bg-gray-700 border border-gray-600 rounded-md py-2 px-3 text-white focus:ring-green-500 focus:border-green-500 @error('date_of_birth') border-red-500 @enderror">
                </div>
                
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-300 mb-1">Photo du Joueur (Optionnel)</label>
                    <input type="file" name="photo" id="photo"
                           class="w-full text-sm text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-green-500 file:text-white
                                hover:file:bg-green-600
                                @error('photo') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Formats acceptés : JPG, PNG (Max 2MB)</p>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-md shadow-md transition duration-150">
                    <i class="fas fa-save mr-2"></i> Soumettre le Profil du Joueur
                </button>
            </div>
        </form>
    </div>
</div>
@endsection