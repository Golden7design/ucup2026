@extends('layouts.admin')

@section('header')
    <div class="flex justify-between items-center">
        {{-- Header : text-white --}}
        <span class="text-white">Gestion des Universités</span>
        {{-- Bouton Ajouter : bg-blue-600, hover:bg-blue-500 --}}
        <a href="{{ route('admin.universities.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-500 transition font-semibold">
            <i class="fas fa-plus mr-2"></i>Ajouter
        </a>
    </div>
@endsection

@section('content')
{{-- Conteneur principal : bg-gray-800, border-gray-700 --}}
<div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-700">
    {{-- Table Head : bg-gray-700, text-gray-300 --}}
    <table class="min-w-full divide-y divide-gray-700">
        <thead class="bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Université</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">Couleurs</th>
                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-300 uppercase tracking-wider">Équipes</th>
                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-300 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        {{-- Table Body : bg-gray-800, divide-gray-700, text-gray-200 --}}
        <tbody class="bg-gray-800 divide-y divide-gray-700">
            @forelse($universities as $university)
            {{-- Row Hover : hover:bg-gray-700 --}}
            <tr class="hover:bg-gray-700 transition duration-150">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        {{-- Logo : bg-gray-700, border-gray-600 --}}
                        <div class="flex-shrink-0 h-10 w-10 bg-gray-700 rounded-full flex items-center justify-center overflow-hidden border border-gray-600">
                            @if($university->logo)
                                <img class="h-10 w-10 object-cover" src="{{ asset('storage/' . $university->logo) }}" alt="">
                            @else
                                <i class="fas fa-university text-gray-400"></i>
                            @endif
                        </div>
                        <div class="ml-4">
                            {{-- Nom : text-white --}}
                            <div class="text-sm font-medium text-white">{{ $university->name }}</div>
                            {{-- Sigle : text-gray-400 --}}
                            <div class="text-sm text-gray-400">{{ $university->short_name }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                    {{ $university->colors ?? 'Non défini' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-blue-400">
                    {{ $university->teams_count }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    {{-- Lien Éditer : text-blue-400, hover:text-blue-300 --}}
                    <a href="{{ route('admin.universities.edit', $university) }}" class="text-blue-400 hover:text-blue-300 mr-3 transition">
                        <i class="fas fa-edit"></i>
                    </a>
                    {{-- Bouton Supprimer : text-red-400, hover:text-red-300 --}}
                    <form action="{{ route('admin.universities.destroy', $university) }}" method="POST" class="inline-block" onsubmit="return confirm('ATTENTION : Supprimer cette université supprime toutes les équipes et données associées. Confirmez ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-300 transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                {{-- Message d'absence : text-gray-400 --}}
                <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                    Aucune université enregistrée. Commencez par en ajouter une !
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-700 text-white">
        {{ $universities->links() }}
    </div>
</div>
@endsection