@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Planifier un Rendez-vous</h1>

    <form action="{{ route('rdvs.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        <!-- Contact Selection -->
        <div class="mb-4">
            <label for="contact_id" class="block text-sm font-medium text-gray-700">Contact</label>
            <select name="contact_id" id="contact_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                @foreach($contacts as $contact)
                    <option value="{{ $contact->id }}">{{ $contact->nom }} {{ $contact->prenom }}</option>
                @endforeach
            </select>
        </div>

        <!-- Date Input -->
        <div class="mb-4">
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="datetime-local" id="date" name="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
        </div>

        <!-- Type Input -->
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
            <input type="text" id="type" name="type" placeholder="Ex: Consultation initiale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg shadow hover:bg-gray-900 transition">
                Planifier
            </button>
        </div>
    </form>
</div>
@endsection