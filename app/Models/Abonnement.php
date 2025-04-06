<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'freelancer_id',
        'contact_id', // Add this to track the client
        'plan',
        'date_debut',
        'date_fin',
        'statut',
        'contracts_count', // Track the number of contracts
    ];

    // Relationship with Freelancer
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    // Relationship with Contact (Client)
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
