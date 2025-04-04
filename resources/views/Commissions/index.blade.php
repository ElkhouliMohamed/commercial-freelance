@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Liste des Commissions</h1>

    <table class="table-auto w-full">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">Freelance</th>
                <th class="px-4 py-2">Montant</th>
                <th class="px-4 py-2">Statut</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commissions as $commission)
            <tr>
                <td class="border px-4 py-2">{{ $commission->freelancer->name }}</td>
                <td class="border px-4 py-2">{{ number_format($commission->montant, 2) }} €</td>
                <td class="border px-4 py-2">{{ $commission->statut }}</td>
                <td class="border px-4 py-2">
                    @if($commission->statut === 'en attente' && Auth::user()->hasRole('Admin|Super Admin'))
                        <form action="{{ route('commissions.approve', $commission) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Approuver</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection