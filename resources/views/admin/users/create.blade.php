@extends('layouts.admin')

@section('header', 'Créer un Utilisateur')

@section('content')
<div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Créer un nouvel utilisateur</h1>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour à la liste
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1">Nom complet</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                       class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" 
                       required>
                @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Adresse Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                       class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" 
                       required>
                @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-300 mb-1">Mot de passe</label>
                <input type="password" name="password" id="password" 
                       class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" 
                       required>
                @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1">Confirmer le mot de passe</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="md:col-span-2">
                <div class="flex items-center">
                    <input type="checkbox" name="is_admin" id="is_admin" value="1" {{ old('is_admin') ? 'checked' : '' }} 
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 rounded">
                    <label for="is_admin" class="ml-2 block text-sm text-gray-300">
                        Administrateur
                    </label>
                </div>
                <p class="text-xs text-gray-400 mt-1">Cochez cette case pour donner des droits d'administrateur à cet utilisateur.</p>
            </div>
        </div>

        <div class="flex justify-end mt-8">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-lg transition duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Enregistrer l'utilisateur
            </button>
        </div>
    </form>
</div>
@endsection
