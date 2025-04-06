<form method="POST" action="{{ route('commissions.approve', $commission) }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="proof">Preuve de paiement (PDF/Image)</label>
        <input type="file" name="proof" id="proof" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Valider le paiement</button>
</form>