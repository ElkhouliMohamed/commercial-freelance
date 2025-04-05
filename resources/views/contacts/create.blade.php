@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8 border-b-2 border-gray-200 pb-2">Ajouter un Contact</h1>

    <div class="max-w-2xl mx-auto bg-white shadow-2xl rounded-xl p-6 sm:p-8">
        <form action="{{ route('contacts.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nom Input -->
            <div>
                <label for="nom" class="block text-sm font-semibold text-gray-700">Nom <span class="text-red-500">*</span></label>
                <input type="text" id="nom" name="nom" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 transition duration-150 ease-in-out
                              @error('nom') border-red-500 @enderror"
                       placeholder="Entrez le nom">
                @error('nom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prénom Input -->
            <div>
                <label for="prenom" class="block text-sm font-semibold text-gray-700">Prénom <span class="text-red-500">*</span></label>
                <input type="text" id="prenom" name="prenom" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 transition duration-150 ease-in-out
                              @error('prenom') border-red-500 @enderror"
                       placeholder="Entrez le prénom">
                @error('prenom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 transition duration-150 ease-in-out
                              @error('email') border-red-500 @enderror"
                       placeholder="Entrez l'email">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Téléphone Input -->
            <div>
                <label for="telephone" class="block text-sm font-semibold text-gray-700">Téléphone</label>
                <input type="text" id="telephone" name="telephone"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 transition duration-150 ease-in-out
                              @error('telephone') border-red-500 @enderror"
                       placeholder="Entrez le numéro de téléphone">
                @error('telephone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Adresse Input -->
            <div>
                <label for="adresse" class="block text-sm font-semibold text-gray-700">Adresse</label>
                <input type="text" id="adresse" name="adresse"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 transition duration-150 ease-in-out
                              @error('adresse') border-red-500 @enderror"
                       placeholder="Entrez l'adresse">
                @error('adresse')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nom de l'entreprise Input -->
            <div>
                <label for="nom_entreprise" class="block text-sm font-semibold text-gray-700">Nom de l'entreprise</label>
                <input type="text" id="nom_entreprise" name="nom_entreprise"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 transition duration-150 ease-in-out
                              @error('nom_entreprise') border-red-500 @enderror"
                       placeholder="Entrez le nom de l'entreprise">
                @error('nom_entreprise')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instagram Input -->
            <div>
                <label for="instagram" class="block text-sm font-semibold text-gray-700">Instagram</label>
                <input type="text" id="instagram" name="instagram"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 transition duration-150 ease-in-out
                              @error('instagram') border-red-500 @enderror"
                       placeholder="Entrez le lien Instagram">
                @error('instagram')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Facebook Input -->
            <div>
                <label for="facebook" class="block text-sm font-semibold text-gray-700">Facebook</label>
                <input type="text" id="facebook" name="facebook"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 transition duration-150 ease-in-out
                              @error('facebook') border-red-500 @enderror"
                       placeholder="Entrez le lien Facebook">
                @error('facebook')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Site Web Input -->
            <div>
                <label for="siteweb" class="block text-sm font-semibold text-gray-700">Site Web</label>
                <input type="text" id="siteweb" name="siteweb"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 transition duration-150 ease-in-out
                              @error('siteweb') border-red-500 @enderror"
                       placeholder="Entrez le lien du site web">
                @error('siteweb')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-indigo-700 transition duration-200 ease-in-out transform hover:-translate-y-1">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection