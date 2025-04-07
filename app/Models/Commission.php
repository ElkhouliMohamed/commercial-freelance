<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'freelancer_id',
        'montant',
        'description',
        'statut',
        'demande_paiement',
        'level'
    ];

    // Relationship to Freelancer
    
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function devis()
    {
        return $this->belongsTo(Devis::class, 'devis_id');
    }
    
}
