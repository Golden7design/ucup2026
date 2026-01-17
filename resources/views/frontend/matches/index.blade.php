@extends('layouts.app')

@section('title', 'Matchs - U-Cup Tournament')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-4 text-white">
        <i class="fas fa-calendar-alt text-yellow-500 mr-3"></i>
        Calendrier des Matchs
    </h1>

    <div class="bg-gray-800 rounded-lg shadow-xl p-4 mb-6 text-white">
        <form method="GET" class="grid md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2 text-gray-400">Statut</label>
                <select name="status" class="w-full border border-gray-700 bg-gray-700 text-white rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Tous</option>
                    <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>À venir</option>
                    <option value="live" {{ request('status') === 'live' ? 'selected' : '' }}>En direct</option>
                    <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>Terminés</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2 text-gray-400">Phase</label>
                <select name="round" class="w-full border border-gray-700 bg-gray-700 text-white rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Toutes</option>
                    <option value="group_stage" {{ request('round') === 'group_stage' ? 'selected' : '' }}>Phase de groupes</option>
                    <option value="quarter_final" {{ request('round') === 'quarter_final' ? 'selected' : '' }}>Quarts de finale</option>
                    <option value="semi_final" {{ request('round') === 'semi_final' ? 'selected' : '' }}>Demi-finales</option>
                    <option value="final" {{ request('round') === 'final' ? 'selected' : '' }}>Finale</option>
                </select>
            </div>

            <div class="md:col-span-2 flex items-end gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                <a href="{{ route('matches.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition">
                    <i class="fas fa-times mr-2"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <div class="space-y-4">
        @forelse($matches as $match)
        <div class="bg-gray-800 rounded-lg shadow-xl hover:shadow-2xl transition p-6 text-white border border-gray-700">
            <div class="flex flex-wrap items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <span class="text-gray-400">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ $match->match_date->format('d/m/Y') }}
                    </span>
                    <span class="text-gray-400">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $match->match_date->format('H:i') }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    @if($match->status === 'live')
                        <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold animate-pulse">
                            <i class="fas fa-circle mr-1"></i>EN DIRECT
                        </span>
                    @elseif($match->status === 'halftime')
                        <span class="bg-orange-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            MI-TEMPS
                        </span>
                    @elseif($match->status === 'finished')
                        <span class="bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            TERMINÉ
                        </span>
                    @else
                        <span class="bg-gray-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            À VENIR
                        </span>
                    @endif

                    @if($match->group)
                        <span class="bg-blue-900 text-blue-300 px-3 py-1 rounded-full text-sm font-semibold">
                            Groupe {{ $match->group }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex-1 flex items-center gap-3">
                    @if($match->homeTeam->university->logo)
                        <img src="{{ asset('storage/' . $match->homeTeam->university->logo) }}" alt="{{ $match->homeTeam->university->name }}" class="h-12 w-12 object-contain bg-white p-1 rounded-full">
                    @else
                        <i class="fas fa-shield-alt text-3xl text-blue-500"></i>
                    @endif
                    <div>
                        <h3 class="font-bold text-lg">{{ $match->homeTeam->university->short_name }}</h3>
                        <p class="text-sm text-gray-400">{{ $match->homeTeam->name }}</p>
                    </div>
                </div>

                <div class="mx-6">
                    @if($match->isFinished() || $match->isLive())
                        <div class="text-3xl font-bold text-yellow-500">
                            {{ $match->home_score }} - {{ $match->away_score }}
                        </div>
                    @else
                        <div class="text-2xl text-gray-500 font-semibold">VS</div>
                    @endif
                </div>

                <div class="flex-1 flex items-center justify-end gap-3">
                    <div class="text-right">
                        <h3 class="font-bold text-lg">{{ $match->awayTeam->university->short_name }}</h3>
                        <p class="text-sm text-gray-400">{{ $match->awayTeam->name }}</p>
                    </div>
                    @if($match->awayTeam->university->logo)
                        <img src="{{ asset('storage/' . $match->awayTeam->university->logo) }}" alt="{{ $match->awayTeam->university->name }}" class="h-12 w-12 object-contain bg-white p-1 rounded-full">
                    @else
                        <i class="fas fa-shield-alt text-3xl text-green-500"></i>
                    @endif
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between border-t border-gray-700 pt-4">
                <span class="text-gray-400 text-sm">
                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $match->venue }}
                </span>
                <a href="{{ route('matches.show', $match) }}" class="text-yellow-500 hover:text-yellow-400 font-semibold">
                    Voir détails <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @empty
        <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center border border-gray-700">
            <i class="fas fa-calendar-times text-6xl text-gray-600 mb-4"></i>
            <p class="text-gray-400 text-lg">Aucun match trouvé</p>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{-- Laravel gère les classes de la pagination, assurez-vous que Tailwind est bien configuré pour les vues de pagination --}}
        {{ $matches->links() }} 
    </div>
</div>
@endsection