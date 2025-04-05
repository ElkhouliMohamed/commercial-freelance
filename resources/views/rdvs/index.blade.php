@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-extrabold text-gray-900 border-b-2 border-gray-200 pb-2">Gestion des Rendez-vous</h1>
            <a href="{{ route('rdvs.create') }}"
                class="bg-gray-800 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                Planifier un Rendez-vous
            </a>
        </div>

        <!-- RDVs Table -->
        <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rdvs as $rdv)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $rdv->contact->nom }} {{ $rdv->contact->prenom }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($rdv->date)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $rdv->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColor =
                                        [
                                            'planifié' => 'bg-blue-100 text-blue-800',
                                            'annulé' => 'bg-red-100 text-red-800',
                                            'confirmé' => 'bg-green-100 text-green-800',
                                            'terminé' => 'bg-purple-100 text-purple-800',
                                        ][strtolower($rdv->statut)] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                    {{ ucfirst($rdv->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                <!-- Create Devis Button -->
                                <a href="{{ route('devis.create', ['rdvId' => $rdv->id]) }}"
                                    class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                                    Créer un Devis
                                </a>

                                <!-- Edit Button -->
                                <a href="{{ route('rdvs.edit', $rdv->id) }}"
                                    class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                                    Modifier
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('rdvs.destroy', $rdv->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-gray-800 text-white px-3 py-1 rounded hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50"
                                        onclick="return confirm('Confirmer la suppression ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 text-lg">
                                Aucun rendez-vous trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (if applicable) -->
        @if ($rdvs->hasPages())
            <div class="mt-6">
                {{ $rdvs->links() }}
            </div>
        @endif
    </div>
@endsection
