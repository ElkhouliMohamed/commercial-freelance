<!-- filepath: c:\Users\Mohamed\Desktop\news\commercial-freelance\resources\views\contacts\index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-extrabold text-gray-900 border-b-2 border-gray-200 pb-2">Gestion des Contacts</h1>
        <a href="{{ route('contacts.create') }}" class="bg-gray-800 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
            Ajouter un Contact
        </a>
    </div>

    <!-- Contacts Table -->
    <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Nom</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Téléphone</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($contacts as $contact)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contact->nom }} {{ $contact->prenom }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contact->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $contact->telephone ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($contact->trashed())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Archivé
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Actif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                        <!-- Edit Button -->
                        <a href="{{ route('contacts.edit', $contact) }}" class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                            Modifier
                        </a>

                        <!-- Restore or Archive Button -->
                        @if($contact->trashed())
                            <form action="{{ route('contacts.restore', $contact->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                                    Restaurer
                                </button>
                            </form>
                        @else
                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50" onclick="return confirm('Confirmer l’archivage ?')">
                                    Archiver
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-lg">
                        Aucun contact trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination (if applicable) -->
    @if($contacts->hasPages())
        <div class="mt-6">
            {{ $contacts->links() }}
        </div>
    @endif
</div>
@endsection