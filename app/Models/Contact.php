<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',         // New field
        'nom_entreprise',  // New field
        'instagram',       // New field
        'facebook',        // New field
        'siteweb',         // New field
        'freelancer_id',
        'statut',
    ];

    /**
     * Get the full name of the contact.
     */
    public function getFullNameAttribute()
    {
        return "{$this->prenom} {$this->nom}";
    }

    /**
     * Relationship: Contact belongs to a Freelancer (User).
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * Relationship: Contact has many RDVs.
     */
    public function rdvs()
    {
        return $this->hasMany(Rdv::class, 'contact_id');
    }

    /**
     * Relationship: Contact has many Devis.
     */
    public function devis()
    {
        return $this->hasMany(Devis::class, 'contact_id');
    }

    /**
     * Scope: Filter contacts by freelancer.
     */
    public function scopeByFreelancer($query, $freelancerId)
    {
        return $query->where('freelancer_id', $freelancerId);
    }

    /**
     * Scope: Filter active contacts.
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope: Filter archived contacts.
     */
    public function scopeArchived($query)
    {
        return $query->onlyTrashed();
    }
}
