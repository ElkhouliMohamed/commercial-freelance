@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails de la Commission #{{ $commission->id }}</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Montant:</strong> {{ number_format($commission->montant, 2) }} MAD</p>
            <p><strong>Niveau:</strong> {{ $commission->niveau }}</p>
            <p><strong>Nombre de Contrats:</strong> {{ $commission->nombre_contrats }}</p>
            <p><strong>Statut:</strong> {{ $commission->statut }}</p>
            <p><strong>Date de Création:</strong> {{ $commission->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Description:</strong> {{ $commission->description }}</p>

            @if($commission->statut == 'Payé' && $commission->payment_proof_path)
                <a href="{{ route('commissions.showProof', $commission->id) }}" class="btn btn-sm btn-success" target="_blank">
                    Voir la Preuve de Paiement
                </a>
            @endif

            <a href="{{ route('commissions.index') }}" class="btn btn-primary mt-3">Retour à la Liste</a>
        </div>
    </div>
</div>
@endsection