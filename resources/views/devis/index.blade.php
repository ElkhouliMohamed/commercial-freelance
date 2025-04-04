{{-- filepath: c:\Users\Mohamed\Desktop\news\commercial-freelance\resources\views\devis\index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Liste des Devis</h1>

    @role('Admin|Account Manager')
        <!-- Add Devis Button -->
        <a href="{{ route('devis.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition mb-4 inline-block">
            Ajouter un Devis
        </a>
    @endrole

    @foreach($rdvs as $rdv)
        <a href="{{ route('devis.create', ['rdv' => $rdv->id]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            Créer un Devis
        </a>
    @endforeach

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">RDV</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Contact</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Freelancer</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Montant</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Statut</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($devis as $devi)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $devi->rdv->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $devi->contact->nom }} {{ $devi->contact->prenom }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $devi->freelancer->name ?? 'Aucun' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $devi->montant }} €</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ ucfirst($devi->statut) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800 flex space-x-2">
                            @role('Admin|Account Manager')
                                <!-- Edit Button -->
                                <a href="{{ route('devis.edit', $devi) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                    Modifier
                                </a>
                                <!-- Delete Button -->
                                <form action="{{ route('devis.destroy', $devi) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition" onclick="return confirm('Confirmer la suppression ?')">
                                        Supprimer
                                    </button>
                                </form>
                            @endrole
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection