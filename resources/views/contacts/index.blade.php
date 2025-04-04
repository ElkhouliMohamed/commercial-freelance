{{-- filepath: c:\Users\Mohamed\Desktop\news\commercial-freelance\resources\views\contacts\index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Gestion des Contacts</h1>

    <!-- Add Contact Button -->
    <div class="mb-4">
        <a href="{{ route('contacts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            Ajouter un Contact
        </a>
    </div>

    <!-- Contacts Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Téléphone</th>
                    <th class="px-4 py-2 text-left">Statut</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($contacts as $contact)
                <tr>
                    <td class="px-4 py-2">{{ $contact->nom }} {{ $contact->prenom }}</td>
                    <td class="px-4 py-2">{{ $contact->email }}</td>
                    <td class="px-4 py-2">{{ $contact->telephone }}</td>
                    <td class="px-4 py-2">
                        @if($contact->trashed())
                            <span class="text-red-500 font-semibold">Archivé</span>
                        @else
                            <span class="text-green-500 font-semibold">Actif</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 flex space-x-2">
                        <!-- Edit Button -->
                        <a href="{{ route('contacts.edit', $contact) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                            Modifier
                        </a>

                        <!-- Restore or Archive Button -->
                        @if($contact->trashed())
                            <form action="{{ route('contacts.restore', $contact->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                                    Restaurer
                                </button>
                            </form>
                        @else
                            <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition" onclick="return confirm('Confirmer l’archivage ?')">
                                    Archiver
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500">
                        Aucun contact trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection