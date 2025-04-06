<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'nom_entreprise',
        'instagram',
        'facebook',
        'siteweb',
        'freelancer_id',
        'statut',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = ['full_name'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the contact's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->prenom} {$this->nom}");
    }

    /**
     * Get the freelancer that owns the contact.
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * Get the RDVs for the contact.
     */
    public function rdvs(): HasMany
    {
        return $this->hasMany(Rdv::class);
    }

    /**
     * Get the devis for the contact.
     */
    public function devis(): HasMany
    {
        return $this->hasMany(Devis::class);
    }

    /**
     * Scope a query to only include contacts for a specific freelancer.
     */
    public function scopeByFreelancer(Builder $query, int $freelancerId): Builder
    {
        return $query->where('freelancer_id', $freelancerId);
    }

    /**
     * Scope a query to only include active contacts.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope a query to only include archived contacts.
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope a query to search contacts by name or email.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
