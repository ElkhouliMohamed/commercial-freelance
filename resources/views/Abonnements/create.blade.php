{{-- filepath: c:\Users\Mohamed\Desktop\news\commercial-freelance\resources\views\Abonnements\create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Ajouter un Abonnement</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('abonnements.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf

        <!-- Freelancer -->
        <div class="mb-4">
            <label for="freelancer_id" class="block text-sm font-medium text-gray-700">Freelancer</label>
            <select id="freelancer_id" name="freelancer_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="">Sélectionner un Freelancer</option>
                @foreach ($freelancers as $freelancer)
                    <option value="{{ $freelancer->id }}">{{ $freelancer->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Plan -->
        <div class="mb-4">
            <label for="plan" class="block text-sm font-medium text-gray-700">Plan</label>
            <select id="plan" name="plan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="">Sélectionner un Plan</option>
                <option value="Basic">Basic</option>
                <option value="Premium">Premium</option>
            </select>
        </div>

        <!-- Date Début -->
        <div class="mb-4">
            <label for="date_debut" class="block text-sm font-medium text-gray-700">Date Début</label>
            <input type="date" id="date_debut" name="date_debut" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <!-- Date Fin -->
        <div class="mb-4">
            <label for="date_fin" class="block text-sm font-medium text-gray-700">Date Fin</label>
            <input type="date" id="date_fin" name="date_fin" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection