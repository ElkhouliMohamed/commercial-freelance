<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rdv extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contact_id',
        'freelancer_id',
        'manager_id',
        'date',
        'type',
        'statut',
    ];

    // ============================
    // Relationships
    // ============================

    /**
     * RDV belongs to a contact.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    /**
     * RDV belongs to a freelancer.
     */
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * RDV belongs to a manager.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * RDV has one devis.
     */
    public function devis()
    {
        return $this->hasOne(Devis::class, 'rdv_id');
    }

    // ============================
    // Scopes
    // ============================

    /**
     * Scope: Filter RDVs by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('statut', $status);
    }

    /**
     * Scope: Filter RDVs by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Filter RDVs by freelancer.
     */
    public function scopeByFreelancer($query, $freelancerId)
    {
        return $query->where('freelancer_id', $freelancerId);
    }

    /**
     * Scope: Filter RDVs by manager.
     */
    public function scopeByManager($query, $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    // ============================
    // Accessors
    // ============================

    /**
     * Get the formatted date of the RDV.
     */
    public function getFormattedDateAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->format('d/m/Y H:i');
    }
}
