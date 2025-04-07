@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Liste des Commissions</h1>

    <div class="overflow-x-auto">
        <table class="table-auto w-full text-sm">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-4 py-2">Freelance</th>
                    <th class="px-4 py-2">Montant (MAD)</th>
                    <th class="px-4 py-2">Commission (MAD)</th>
                    <th class="px-4 py-2">Statut</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commissions as $commission)
                    <tr class="hover:bg-gray-100">
                        <td class="border px-4 py-2">{{ $commission->freelancer->name }}</td>
                        <td class="border px-4 py-2">{{ number_format($commission->montant, 2) }} MAD</td>
                        <td class="border px-4 py-2">{{ number_format($commission->commission, 2) }} MAD</td>
                        <td class="border px-4 py-2">{{ $commission->statut }}</td>
                        <td class="border px-4 py-2">
                            @if($commission->statut === 'En Attente' && Auth::user()->hasRole('Admin|Super Admin'))
                                <form action="{{ route('commissions.approve', $commission) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Approuver</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Aucune commission trouvée.</td>
                    </tr>
                @endforelse
            </tbody>a
        </table>
    </div>
</div>
@endsection