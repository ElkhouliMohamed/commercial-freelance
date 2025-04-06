<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devis extends Model
{
    use HasFactory, SoftDeletes;

    // Fillable attributes for mass assignment
    protected $fillable = [
        'rdv_id',        // Link to the appointment
        'contact_id',    // Link to the contact
        'freelancer_id', // Link to the freelancer
        'montant',       // Total amount of the quote
        'statut',        // Status (Draft, Pending, Accepted, Rejected, Cancelled, etc.)
        'date_validite', // Validity date
        'notes',         // Notes or comments
    ];

    // Casts for data types
    protected $casts = [
        'montant' => 'float',
        'date_validite' => 'date',
        'statut' => 'string',
    ];

    // Dates to handle (including soft deletes)
    protected $dates = ['deleted_at', 'date_validite'];

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
     * Relationship: Devis belongs to a Freelancer (User).
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * Relationship: Devis belongs to many Plans.
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'devis_plan', 'devis_id', 'plan_id')->withTimestamps();
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

    /**
     * Scope: Filter devis by freelancer.
     */
    public function scopeByFreelancer($query, $freelancerId)
    {
        return $query->where('freelancer_id', $freelancerId);
    }

    /**
     * Default status for new records.
     */
    protected $attributes = [
        'statut' => 'Brouillon',
    ];
}
