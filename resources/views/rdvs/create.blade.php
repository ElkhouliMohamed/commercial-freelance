@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- En-tête -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-3">Planifier un Rendez-vous</h1>
            <p class="text-lg text-gray-600">Réservez votre consultation avec notre équipe</p>
        </div>

        <!-- Carte du formulaire -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="p-8 sm:p-10">
                <form action="{{ route('rdvs.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Sélection du Contact -->
                    <div class="space-y-2">
                        <label for="contact_id" class="block text-sm font-medium text-gray-700">Sélectionner un Contact</label>
                        <div class="relative">
                            <select name="contact_id" id="contact_id" class="appearance-none block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-700 transition-all duration-200" required>
                                <option value="" disabled selected>Choisir un contact</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}">{{ $contact->nom }} {{ $contact->prenom }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Sélection des Forfaits -->
                    <div class="space-y-2">
                        <label for="plans" class="block text-sm font-medium text-gray-700">Sélectionner des Forfaits</label>
                        <select name="plans[]" id="plans" multiple class="multiselect block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-700 transition-all duration-200" required>
                            @foreach($plans as $plan)
                                <option value="{{ $plan->id }}" class="py-2">{{ $plan->name }} - {{ number_format($plan->price, 2) }} MAD</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl/Cmd pour sélectionner plusieurs options</p>
                    </div>

                    <!-- Date et Type -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date et Heure -->
                        <div class="space-y-2">
                            <label for="date" class="block text-sm font-medium text-gray-700">Date et Heure</label>
                            <div class="relative">
                                <input type="datetime-local" id="date" name="date" class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-700 transition-all duration-200" required>
                            </div>
                        </div>

                        <!-- Type de Rendez-vous -->
                        <div class="space-y-2">
                            <label for="type" class="block text-sm font-medium text-gray-700">Type de Rendez-vous</label>
                            <div class="relative">
                                <select name="type" id="type" class="appearance-none block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-700 transition-all duration-200" required>
                                    <option value="" disabled selected>Sélectionner un type</option>
                                    <option value="Consultation">Consultation</option>
                                    <option value="Suivi">Suivi</option>
                                    <option value="Autre">Autre</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes Complémentaires</label>
                        <textarea id="notes" name="notes" rows="4" class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-700 transition-all duration-200" placeholder="Des exigences particulières ou des notes..."></textarea>
                    </div>

                    <!-- Bouton de Soumission -->
                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center items-center px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-lg shadow-md hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:-translate-y-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            Planifier le Rendez-vous
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .multiselect option {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
    }
    .multiselect option:checked {
        background-color: #3b82f6;
        color: white;
    }
    select:focus, input:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
</style>
@endsection