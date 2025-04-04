@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Créer un Devis</h1>

    <!-- RDV Information -->
    <div class="mb-6 bg-gray-100 p-4 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Informations du Rendez-vous</h2>
        <p><strong>Contact:</strong> {{ $rdv->contact->nom }} {{ $rdv->contact->prenom }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($rdv->date)->format('d/m/Y H:i') }}</p>
        <p><strong>Type:</strong> {{ $rdv->type }}</p>
        <p><strong>Freelancer Assigné:</strong> {{ $rdv->freelancer->name ?? 'Aucun' }}</p>
    </div>


    <!-- Create Devis Form -->
    <form action="{{ route('devis.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf

        <!-- RDV ID (Hidden) -->
        <input type="hidden" name="rdv_id" value="{{ $rdv->id }}">

        <!-- Contact ID (Hidden) -->
        <input type="hidden" name="contact_id" value="{{ $rdv->contact->id }}">

        <!-- Freelancer Selection -->
        <div class="mb-4">
            <label for="freelance_id" class="block text-sm font-medium text-gray-700">Freelancer</label>
            <select id="freelance_id" name="freelance_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                <option value="">Aucun</option>
                @foreach($freelancers as $freelancer)
                    <option value="{{ $freelancer->id }}">{{ $freelancer->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Montant Input -->
        <div class="mb-4">
            <label for="montant" class="block text-sm font-medium text-gray-700">Montant (€)</label>
            <input type="number" id="montant" name="montant" step="0.01" placeholder="Ex: 100.00"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
        </div>

        <!-- Statut Input -->
        <div class="mb-4">
            <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
            <select id="statut" name="statut"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                <option value="brouillon">Brouillon</option>
                <option value="valide">Valide</option>
                <option value="expiré">Expiré</option>
            </select>
        </div>

        <!-- Notes -->
        <div class="mb-4">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea id="notes" name="notes" rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                placeholder="Ajouter des notes ou des commentaires..."></textarea>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600 transition">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection