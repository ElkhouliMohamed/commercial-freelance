@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-extrabold text-gray-900 border-b-2 border-gray-200 pb-2">Liste des Devis</h1>

            @role('Admin|Account Manager')
                <a href="{{ route('devis.create', ['rdvId' => 1]) }}"
                    class="bg-gray-800 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                    Ajouter un Devis
                </a>
            @endrole
        </div>

        <!-- Devis Table -->
        <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">RDV</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Freelancer</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($devis as $devi)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $devi->rdv->id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $devi->contact->nom ?? 'N/A' }} {{ $devi->contact->prenom ?? '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $devi->freelancer->name ?? 'Aucun' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($devi->montant, 2) }} €</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColor = [
                                        'en attente' => 'bg-yellow-100 text-yellow-800',
                                        'accepté' => 'bg-green-100 text-green-800',
                                        'refusé' => 'bg-red-100 text-red-800',
                                        'annulé' => 'bg-gray-100 text-gray-800',
                                        'brouillon' => 'bg-blue-100 text-blue-800',
                                    ][strtolower($devi->statut)] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst($devi->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                @role('Admin|Account Manager')
                                    <!-- Edit Button -->
                                    <a href="{{ route('devis.edit', $devi->id) }}"
                                        class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                                        Modifier
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('devis.destroy', $devi->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50"
                                            onclick="return confirm('Confirmer la suppression ?')">
                                            Supprimer
                                        </button>
                                    </form>
                                @endrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 text-lg">
                                Aucun devis trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (if applicable) -->
        @if ($devis->hasPages())
            <div class="mt-6">
                {{ $devis->links() }}
            </div>
        @endif
    </div>
@endsection