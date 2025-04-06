@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-4xl font-extrabold text-gray-900 border-b-2 border-gray-200 pb-2">Gestion des Plans</h1>

            <a href="{{ route('plans.create') }}"
               class="bg-gray-800 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                Ajouter un Plan
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Plans Table -->
        <div class="bg-white shadow-2xl rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Prix</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($plans as $plan)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $plan->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($plan->price, 2) }} MAD</td>
                            <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                <!-- Edit Button with Icon -->
                                <a href="{{ route('plans.edit', $plan->id) }}"
                                   class="bg-gray-800 text-white px-3 py-1 rounded flex items-center space-x-2 hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                                    <i class="fas fa-edit"></i>
                                    <span>Modifier</span>
                                </a>

                                <!-- Delete Button with Icon and SweetAlert -->
                                <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" class="inline-block" id="deleteForm{{ $plan->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="bg-gray-800 text-white px-3 py-1 rounded flex items-center space-x-2 hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50 cursor-pointer"
                                            onclick="confirmDelete({{ $plan->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                        <span>Supprimer</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 text-lg">
                                Aucun plan trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination (if applicable) -->
        <div class="mt-6">
            {{ $plans->links() }}
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDelete(planId) {
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Cette action est irréversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('deleteForm' + planId);
                        if (form) {
                            form.submit();
                        } else {
                            console.error('Formulaire non trouvé pour l\'ID:', planId);
                        }
                    }
                }).catch(error => {
                    console.error('Erreur lors de la confirmation:', error);
                });
            }

            // Vérification que la fonction est bien définie
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Fonction confirmDelete définie:', typeof confirmDelete === 'function');
            });
        </script>
    @endpush
@endsection