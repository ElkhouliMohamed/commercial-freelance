<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'rdv_id',        // Lien vers le rendez-vous
        'contact_id',    // Lien vers le contact
        'freelancer_id', // Lien vers le freelancer
        'service_id',    // Lien vers le service
        'montant',       // Montant total du devis
        'statut',        // Statut (Brouillon, Valide, Expiré, etc.)
        'date_validite', // Date de validité
        'notes',         // Notes ou commentaires
    ];

    // Casts pour les types de données
    protected $casts = [
        'montant' => 'float',
        'date_validite' => 'date',
        'statut' => 'string',
    ];

    /**
     * Relationship: Devis belongs to an RDV.
     */
    public function rdv()
    {
        return $this->belongsTo(Rdv::class, 'rdv_id');
    }

    /**
     * Relationship: Devis belongs to a Contact.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * Relationship: Devis belongs to a freelancer.
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelance_id');
    }

    /**
     * Relationship: Devis belongs to a Service.
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    /**
     * Scope: Filter devis by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('statut', $status);
    }

    /**
     * Scope: Filter devis by validity date.
     */
    public function scopeValid($query)
    {
        return $query->where('date_validite', '>=', now());
    }
}
