@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div class="mb-4 md:mb-0">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">Gestion des Contacts</h1>
            <p class="text-gray-600 mt-2">Liste de tous vos contacts professionnels</p>
        </div>
        <a href="{{ route('contacts.create') }}" 
           class="flex items-center justify-center bg-indigo-600 text-white px-6 py-3 rounded-lg shadow hover:bg-indigo-700 transition-all duration-200 transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Ajouter un Contact
        </a>
    </div>

    <!-- Contacts Table -->
    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contacts as $contact)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-indigo-600 font-medium">{{ strtoupper(substr($contact->nom, 0, 1)) }}{{ strtoupper(substr($contact->prenom, 0, 1)) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $contact->nom }} {{ $contact->prenom }}</div>
                                    <div class="text-sm text-gray-500">{{ $contact->entreprise ?? 'Sans entreprise' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $contact->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $contact->telephone ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($contact->trashed())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Archivé
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Actif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <!-- Edit Button -->
                                <a href="{{ route('contacts.edit', $contact) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out"
                                   title="Modifier">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>

                                <!-- Restore or Archive Button -->
                                @if($contact->trashed())
                                    <form action="{{ route('contacts.restore', $contact->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" 
                                                class="text-green-600 hover:text-green-900 transition duration-150 ease-in-out"
                                                title="Restaurer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline-block archive-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition duration-150 ease-in-out"
                                                title="Archiver">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-lg font-medium">Aucun contact trouvé</p>
                                <p class="text-sm mt-2">Commencez par ajouter votre premier contact</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($contacts->hasPages())
        <div class="mt-6 px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $contacts->links() }}
        </div>
    @endif
</div>

<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle archive forms
        const archiveForms = document.querySelectorAll('.archive-form');
        
        archiveForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Confirmer l\'archivage',
                    text: "Êtes-vous sûr de vouloir archiver ce contact ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, archiver',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Show success message if session has success
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès !',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        // Show error message if session has errors
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                html: `
                    <ul class="text-left">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                `,
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
@endsection