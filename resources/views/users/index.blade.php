@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-extrabold text-gray-900 border-b-2 border-gray-200 pb-2">Gestion des Utilisateurs</h1>
        <a href="{{ route('users.create') }}" class="bg-gray-800 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
            Ajouter un Utilisateur
        </a>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $user->roles->pluck('name')->join(', ') ?: 'Aucun rôle' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                        <!-- Edit Button -->
                        <a href="{{ route('users.edit', $user) }}" class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                            Modifier
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50" onclick="return confirm('Confirmer la suppression ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 text-lg">
                        Aucun utilisateur trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection