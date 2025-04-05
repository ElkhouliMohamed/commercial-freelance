@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Gestion des Rendez-vous</h1>

        <!-- Add RDV Button -->
        <div class="mb-4">
            <a href="{{ route('rdvs.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                Planifier un Rendez-vous
            </a>
        </div>

        <!-- RDVs Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium">Contact</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Statut</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($rdvs as $rdv)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $rdv->contact->nom }} {{ $rdv->contact->prenom }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ \Carbon\Carbon::parse($rdv->date)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $rdv->type }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if ($rdv->statut === 'planifié')
                                    <span class="text-green-600 font-semibold">Planifié</span>
                                @elseif($rdv->statut === 'annulé')
                                    <span class="text-red-600 font-semibold">Annulé</span>
                                @else
                                    <span class="text-gray-600 font-semibold">{{ ucfirst($rdv->statut) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('devis.create', ['rdvId' => $rdv->id]) }}"
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                    Créer un Devis
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Aucun rendez-vous trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
