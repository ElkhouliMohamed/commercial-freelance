@extends('layouts.app')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8 max-w-6xl">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Créer un Devis</h1>
            <div class="flex space-x-2">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    Retour
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="font-medium">Veuillez corriger les erreurs suivantes :</h3>
                </div>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (!$rdv->contact || $rdv->contact->trashed())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="font-medium">Contact non disponible</h3>
                    <p class="text-sm mt-1">Ce contact n'est plus actif ou a été archivé. Impossible de créer un devis.</p>
                </div>
            </div>
        @else
            <!-- RDV Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Informations du Rendez-vous
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="px-6 py-4">
                            <h3 class="text-sm font-medium text-gray-500">Contact</h3>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ $rdv->contact->nom }}
                                {{ $rdv->contact->prenom }}</p>
                        </div>
                        <div class="px-6 py-4">
                            <h3 class="text-sm font-medium text-gray-500">Email</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $rdv->contact->email ?? 'Non renseigné' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="px-6 py-4">
                            <h3 class="text-sm font-medium text-gray-500">Téléphone</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $rdv->contact->telephone ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="px-6 py-4">
                            <h3 class="text-sm font-medium text-gray-500">Adresse</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $rdv->contact->adresse ?? 'Non renseignée' }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="px-6 py-4">
                            <h3 class="text-sm font-medium text-gray-500">Entreprise</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $rdv->contact->nom_entreprise ?? 'Non renseigné' }}</p>
                        </div>
                        <div class="px-6 py-4">
                            <h3 class="text-sm font-medium text-gray-500">Date</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($rdv->date)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="px-6 py-4">
                            <h3 class="text-sm font-medium text-gray-500">Réseaux sociaux</h3>
                            <div class="mt-1 flex space-x-4">
                                <span class="inline-flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-1 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                    </svg>
                                    {{ $rdv->contact->instagram ?: 'Non' }}
                                </span>
                                <span class="inline-flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z" />
                                    </svg>
                                    {{ $rdv->contact->facebook ?: 'Non' }}
                                </span>
                            </div>
                        </div>
                        <div class="px-6 py-4">
                            <h3 class="text-sm font-medium text-gray-500">Freelancer</h3>
                            <p class="mt-1 text-sm text-gray-900">{{ $rdv->freelancer->name ?? 'Non assigné' }}</p>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <h3 class="text-sm font-medium text-gray-500">Note</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $rdv->notes ?? 'Non ' }}</p>
                    </div>
                </div>
            </div>

            <!-- Devis Form -->
            <form action="{{ route('devis.store') }}" method="POST"
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @csrf
                <input type="hidden" name="rdv_id" value="{{ $rdv->id }}">
                <input type="hidden" name="contact_id" value="{{ $rdv->contact->id }}">

                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Détails du Devis
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date Validité -->
                        <div>
                            <label for="date_validite" class="block text-sm font-medium text-gray-700 mb-1">Date de
                                Validité</label>
                            <div class="relative">
                                <input type="date" id="date_validite" name="date_validite"
                                    value="{{ old('date_validite', now()->addDays(30)->toDateString()) }}"
                                    class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Freelancer Selection -->
                        <div>
                            <label for="freelancer_id"
                                class="block text-sm font-medium text-gray-700 mb-1">Freelancer</label>
                            <select id="freelancer_id" name="freelancer_id"
                                class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Aucun</option>
                                @foreach ($freelancers as $freelancer)
                                    <option value="{{ $freelancer->id }}"
                                        {{ old('freelancer_id', $rdv->freelancer_id) == $freelancer->id ? 'selected' : '' }}>
                                        {{ $freelancer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Multi-Select Plan Selection -->
                    <!-- Multi-Select Plan Selection -->
                    <div>
                        <label for="plans" class="block text-sm font-medium text-gray-700 mb-1">Plans</label>
                        <select id="plans" name="plans[]" multiple
                            class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 select2"
                            data-placeholder="Sélectionnez des plans">
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}"
                                    {{ in_array($plan->id, old('plans', [])) ? 'selected' : '' }}>
                                    {{ $plan->name }} - {{ number_format($plan->price, 2) }} MAD
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Maintenez la touche Ctrl (ou Cmd sur Mac) pour sélectionner
                            plusieurs plans.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Montant Input -->
                        <div>
                            <label for="montant" class="block text-sm font-medium text-gray-700 mb-1">Montant
                                (MAD)</label>
                            <div class="relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">MAD</span>
                                </div>
                                <input type="number" id="montant" name="montant" step="0.01"
                                    value="{{ old('montant') }}" placeholder="0.00"
                                    class="block w-full pl-10 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>
                        </div>

                        <!-- Statut Input -->
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select id="statut" name="statut"
                                class="block w-full pl-4 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="Brouillon" {{ old('statut') === 'Brouillon' ? 'selected' : '' }}>Brouillon
                                </option>
                                <option value="En Attente" {{ old('statut') === 'En Attente' ? 'selected' : '' }}>En
                                    Attente</option>
                                <option value="Accepté" {{ old('statut') === 'Accepté' ? 'selected' : '' }}>Accepté
                                </option>
                                <option value="Refusé" {{ old('statut') === 'Refusé' ? 'selected' : '' }}>Refusé</option>
                                <option value="Annulé" {{ old('statut') === 'Annulé' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea id="notes" name="notes" rows="4"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ajouter des notes ou des commentaires...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                            </path>
                        </svg>
                        Enregistrer le devis
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection
<script>
    $(document).ready(function() {
        $('.select2').select2({
            allowClear: true,
            width: '100%', // Ensures the select2 matches the width of the input
            theme: 'default' // You can change this to 'classic' or other themes if needed
        });
    });
</script>
<style>
    .select2-container .select2-selection--multiple {
        @apply border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:ring-2 focus:ring-offset-2;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        @apply bg-blue-100 text-blue-800 border border-blue-300;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        @apply text-blue-800 hover:text-blue-600;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        @apply border-blue-500 ring-2 ring-blue-500 ring-offset-2;
    }

    .select2-dropdown {
        @apply border border-gray-300 rounded-lg shadow-lg bg-white;
    }

    .select2-search__field {
        @apply border-gray-300 rounded-lg;
    }
</style>
