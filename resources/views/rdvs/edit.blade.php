@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8">Modifier un Rendez-vous</h1>

    <form action="{{ route('rdvs.update', $rdv->id) }}" method="POST" class="bg-white shadow-lg rounded-lg p-8 space-y-6">
        @csrf
        @method('PUT')

        <!-- Contact Selection -->
        <div>
            <label for="contact_id" class="block text-lg font-medium text-gray-700 mb-2">Contact</label>
            <select name="contact_id" id="contact_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700" required>
                <option value="" disabled selected>Choisir un contact</option>
                @foreach($contacts as $contact)
                    <option value="{{ $contact->id }}" {{ $contact->id == $rdv->contact_id ? 'selected' : '' }}>{{ $contact->nom }} {{ $contact->prenom }}</option>
                @endforeach
            </select>
        </div>

        <!-- Multi-Select Plan Selection -->
        <div>
            <label for="plans" class="block text-lg font-medium text-gray-700 mb-2">Plans</label>
            <select name="plans[]" id="plans" multiple class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700" required>
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ in_array($plan->id, $rdv->plans->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $plan->name }} - {{ number_format($plan->price, 2) }} MAD</option>
                @endforeach
            </select>
        </div>

        <!-- Date Input -->
        <div>
            <label for="date" class="block text-lg font-medium text-gray-700 mb-2">Date</label>
            <input type="datetime-local" id="date" name="date" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700" value="{{ old('date', $rdv->date) }}" required>
        </div>

        <!-- Type Input -->
        <div>
            <label for="type" class="block text-lg font-medium text-gray-700 mb-2">Type</label>
            <select name="type" id="type" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700" required>
                <option value="Consultation" {{ $rdv->type == 'Consultation' ? 'selected' : '' }}>Consultation</option>
                <option value="Suivi" {{ $rdv->type == 'Suivi' ? 'selected' : '' }}>Suivi</option>
                <option value="Autre" {{ $rdv->type == 'Autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <!-- Notes Input -->
        <div>
            <label for="notes" class="block text-lg font-medium text-gray-700 mb-2">Notes</label>
            <textarea id="notes" name="notes" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-700" placeholder="Ajouter des notes (facultatif)">{{ old('notes', $rdv->notes) }}</textarea>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
                Modifier
            </button>
        </div>
    </form>
</div>
@endsection
