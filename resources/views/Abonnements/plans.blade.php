@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Choisissez parmi nos différents plans d'abonnement</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse ($plans as $plan)
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $plan->name }}</h2>
                <p class="text-lg font-semibold text-blue-600 mb-4">{{ number_format($plan->price, 2) }} MAD / mois</p>
                <ul class="list-disc pl-5 mb-4 text-gray-700">
                    @foreach (explode("\n", $plan->description) as $feature)
                        <li>{{ $feature }}</li>
                    @endforeach
                </ul>
                <a href="{{ route('abonnements.create', ['plan' => $plan->name]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Sélectionner ce plan
                </a>
            </div>
        @empty
            <p class="text-gray-500">Aucun plan disponible pour le moment.</p>
        @endforelse
    </div>
</div>
@endsection