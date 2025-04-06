@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Modifier le Devis</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('devis.update', $devis->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="montant" class="form-label">Montant</label>
                <input type="number" name="montant" id="montant" class="form-control"
                    value="{{ old('montant', $devis->montant) }}" required>
            </div>

            <div class="mb-3">
                <label for="statut" class="form-label">Statut</label>
                <select name="statut" id="statut" class="form-control" required>
                    <option value="En attente" {{ $devis->statut == 'En attente' ? 'selected' : '' }}>En attente</option>
                    <option value="Accepté" {{ $devis->statut == 'Accepté' ? 'selected' : '' }}>Accepté</option>
                    <option value="Refusé" {{ $devis->statut == 'Refusé' ? 'selected' : '' }}>Refusé</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="freelance_id" class="form-label">Freelancer</label>
                <select name="freelance_id" id="freelance_id" class="form-control">
                    <option value="">Aucun</option>
                    @foreach ($freelancers as $freelancer)
                        <option value="{{ $freelancer->id }}"
                            {{ $devis->freelance_id == $freelancer->id ? 'selected' : '' }}>
                            {{ $freelancer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Multi-Select Plan Selection -->
            <div class="mb-4">
                <label for="plans" class="block text-sm font-medium text-gray-700">Plans</label>
                <select id="plans" name="plans[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ in_array($plan->id, $devis->plans->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $plan->name }} - {{ number_format($plan->price, 2) }} €
                        </option>
                    @endforeach
                </select>
                <p class="text-sm text-gray-500 mt-1">Maintenez la touche Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs plans.</p>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $devis->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('devis.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
@endsection
