{{-- filepath: c:\Users\Mohamed\Desktop\news\commercial-freelance\resources\views\Abonnements\index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gestion des Abonnements</h1>
        <a href="{{ route('abonnements.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
            Ajouter un Abonnement
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Freelancer</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Plan</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Date Début</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Date Fin</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($abonnements as $abonnement)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $abonnement->freelancer->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $abonnement->plan }}</td>
                        <td class="px-6 py-4">{{ $abonnement->date_debut->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $abonnement->date_fin->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 flex space-x-2">
                            <!-- Edit Button -->
                            <a href="{{ route('abonnements.edit', $abonnement->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                Modifier
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('abonnements.destroy', $abonnement->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Aucun abonnement trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $abonnements->links() }}
    </div>
</div>
@endsection