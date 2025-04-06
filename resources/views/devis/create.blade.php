@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 border-b-2 border-gray-200 pb-2 mb-6">Créer un Devis</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- RDV Information -->
        <div class="bg-white shadow-2xl rounded-lg overflow-hidden p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Informations du Rendez-vous</h2>
            <table class="w-full divide-y divide-gray-200">
                <tbody>
                    <tr>
                        <td class="p-4 font-semibold bg-gray-100">Contact</td>
                        <td class="p-4">{{ $rdv->contact->nom }} {{ $rdv->contact->prenom }}</td>
                    </tr>
                    <tr>
                        <td class="p-4 font-semibold bg-gray-100">Email</td>
                        <td class="p-4">{{ $rdv->contact->email ?? 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <td class="p-4 font-semibold bg-gray-100">Téléphone</td>
                        <td class="p-4">{{ $rdv->contact->telephone ?? 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <td class="p-4 font-semibold bg-gray-100">Adresse</td>
                        <td class="p-4">{{ $rdv->contact->adresse ?? 'Non renseignée' }}</td>
                    </tr>
                    <tr>
                        <td class="p-4 font-semibold bg-gray-100">Nom de l'entreprise</td>
                        <td class="p-4">{{ $rdv->contact->nom_entreprise ?? 'Non renseigné' }}</td>
                    </tr>
                    <tr>
                        <td class="p-4 font-semibold bg-gray-100">Date</td>
                        <td class="p-4">{{ \Carbon\Carbon::parse($rdv->date)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="p-4 font-semibold bg-gray-100">Type</td>
                        <td class="p-4">{{ $rdv->type }}</td>
                    </tr>
                    <tr>
                        <td class="p-4 font-semibold bg-gray-100">Freelancer</td>
                        <td class="p-4">{{ $rdv->freelancer->name ?? 'Non assigné' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form action="{{ route('devis.store') }}" method="POST" class="bg-white shadow-2xl rounded-lg p-6">
            @csrf
            <input type="hidden" name="rdv_id" value="{{ $rdv->id }}">
            <input type="hidden" name="contact_id" value="{{ $rdv->contact->id }}">

            <!-- Date Validité (Visible Input) -->
            <div class="mb-4">
                <label for="date_validite" class="block text-sm font-medium text-gray-700">Date de Validité</label>
                <input type="date" id="date_validite" name="date_validite" value="{{ old('date_validite', now()->addDays(30)->toDateString()) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <!-- Freelancer Selection -->
            <div class="mb-4">
                <label for="freelancer_id" class="block text-sm font-medium text-gray-700">Freelancer</label>
                <select id="freelancer_id" name="freelancer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Aucun</option>
                    @foreach ($freelancers as $freelancer)
                        <option value="{{ $freelancer->id }}" {{ old('freelancer_id', $rdv->freelancer_id) == $freelancer->id ? 'selected' : '' }}>
                            {{ $freelancer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Multi-Select Plan Selection -->
            <div class="mb-4">
                <label for="plans" class="block text-sm font-medium text-gray-700">Plans</label>
                <select id="plans" name="plans[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}" {{ in_array($plan->id, old('plans', [])) ? 'selected' : '' }}>
                            {{ $plan->name }} - {{ number_format($plan->price, 2) }} €
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Maintenez la touche Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs plans.</p>
            </div>

            <!-- Montant Input -->
            <div class="mb-4">
                <label for="montant" class="block text-sm font-medium text-gray-700">Montant (€)</label>
                <input type="number" id="montant" name="montant" step="0.01" value="{{ old('montant') }}" placeholder="Ex: 100.00" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
            </div>

            <!-- Statut Input -->
            <div class="mb-4">
                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                <select id="statut" name="statut" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="Brouillon" {{ old('statut') === 'Brouillon' ? 'selected' : '' }}>Brouillon</option>
                    <option value="En Attente" {{ old('statut') === 'En Attente' ? 'selected' : '' }}>En Attente</option>
                    <option value="Accepté" {{ old('statut') === 'Accepté' ? 'selected' : '' }}>Accepté</option>
                    <option value="Refusé" {{ old('statut') === 'Refusé' ? 'selected' : '' }}>Refusé</option>
                    <option value="Annulé" {{ old('statut') === 'Annulé' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>

            <!-- Notes -->
            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ajouter des notes ou des commentaires...">{{ old('notes') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
@endsection