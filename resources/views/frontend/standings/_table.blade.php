<div class="overflow-x-auto">
    {{-- Séparateur de tableau : divide-gray-700 --}}
    <table class="min-w-full divide-y divide-gray-700">
        {{-- En-tête : bg-gray-700, texte gris clair --}}
        <thead class="bg-gray-700">
            <tr>
                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Équipe</th>
                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">J</th>
                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">G</th>
                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">N</th>
                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">P</th>
                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">BP</th>
                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">BC</th>
                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">Diff</th>
                <th scope="col" class="px-3 py-3 text-center text-sm font-bold text-white">Pts</th>
            </tr>
        </thead>
        {{-- Corps du tableau : bg-gray-800, séparateur : divide-gray-700 --}}
        <tbody class="bg-gray-800 divide-y divide-gray-700">
            @foreach($standings as $index => $standing)
                {{-- Ligne qualifiée : bg-green-900 (pour se démarquer), hover:bg-gray-700 --}}
                <tr class="{{ $index < 2 && !$isGeneral ? 'bg-green-900/50 font-semibold' : '' }} hover:bg-gray-700 transition duration-150">
                    {{-- Nom de l'équipe : text-white --}}
                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-white">
                        <a href="{{ route('teams.show', $standing->team_id) }}" class="flex items-center">
                            {{-- Rang : text-gray-400 --}}
                            <span class="w-4 text-gray-400 mr-2">{{ $index + 1 }}.</span>
                            @if($standing->team->university->logo)
                                <img src="{{ asset('storage/' . $standing->team->university->logo) }}" alt="{{ $standing->team->university->name }}" class="h-6 w-6 object-contain mr-3">
                            @endif
                            {{ $standing->team->university->short_name }}
                        </a>
                    </td>
                    {{-- Statistiques : text-gray-400 --}}
                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-400">{{ $standing->played }}</td>
                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-400">{{ $standing->won }}</td>
                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-400">{{ $standing->drawn }}</td>
                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-400">{{ $standing->lost }}</td>
                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-400">{{ $standing->goals_for }}</td>
                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-400">{{ $standing->goals_against }}</td>
                    <td class="px-3 py-4 whitespace-nowrap text-center text-sm text-gray-400">{{ $standing->goal_difference }}</td>
                    {{-- Points : text-indigo-400 --}}
                    <td class="px-3 py-4 whitespace-nowrap text-center text-lg font-extrabold text-indigo-400">{{ $standing->points }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>