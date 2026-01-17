@extends('layouts.admin')

@section('header', 'Gestion des Utilisateurs')

@section('content')
<div class="bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-700">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Liste des Utilisateurs</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center">
            <i class="fas fa-plus-circle mr-2"></i>
            Ajouter un Utilisateur
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-600 text-white p-4 rounded-lg mb-6 flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-600 text-white p-4 rounded-lg mb-6 flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-700 rounded-lg overflow-hidden">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date de création</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-700 divide-y divide-gray-600">
                @forelse($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-white">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->is_admin)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-600 text-white">
                            Administrateur
                        </span>
                        @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-600 text-white">
                            Utilisateur
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-400 hover:text-blue-300 mr-2">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
