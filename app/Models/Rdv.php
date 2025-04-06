<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

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
        'notes',
        'location',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'formatted_date',
        'is_past',
        'is_upcoming',
    ];

    // ============================
    // Constants for status and types
    // ============================

    public const STATUS_PLANNED = 'planifié';
    public const STATUS_CONFIRMED = 'confirmé';
    public const STATUS_CANCELLED = 'annulé';
    public const STATUS_COMPLETED = 'terminé';

    public const TYPE_PHYSICAL = 'physique';
    public const TYPE_VIRTUAL = 'virtuel';
    public const TYPE_PHONE = 'téléphonique';

    /**
     * Get all available status options.
     *
     * @return array<string>
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PLANNED,
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
            self::STATUS_COMPLETED,
        ];
    }

    /**
     * Get all available type options.
     *
     * @return array<string>
     */
    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_PHYSICAL,
            self::TYPE_VIRTUAL,
            self::TYPE_PHONE,
        ];
    }

    // ============================
    // Relationships
    // ============================

    /**
     * RDV belongs to a contact.
     */
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * RDV belongs to a freelancer.
     */
    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    /**
     * RDV belongs to a manager.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * RDV has one devis.
     */
    public function devis(): HasOne
    {
        return $this->hasOne(Devis::class);
    }

    /**
     * RDV belongs to many plans.
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'rdv_plan', 'rdv_id', 'plan_id')
            ->withTimestamps();
    }

    // ============================
    // Scopes
    // ============================

    /**
     * Scope: Filter RDVs by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('statut', $status);
    }

    /**
     * Scope: Filter RDVs by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Filter RDVs by freelancer.
     */
    public function scopeByFreelancer($query, int $freelancerId)
    {
        return $query->where('freelancer_id', $freelancerId);
    }

    /**
     * Scope: Filter RDVs by manager.
     */
    public function scopeByManager($query, int $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    /**
     * Scope: Upcoming RDVs.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())
            ->where('statut', self::STATUS_PLANNED);
    }

    /**
     * Scope: Past RDVs.
     */
    public function scopePast($query)
    {
        return $query->where('date', '<', now());
    }

    /**
     * Scope: RDVs for today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    // ============================
    // Accessors & Mutators
    // ============================

    /**
     * Get the formatted date of the RDV.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('d/m/Y H:i');
    }

    /**
     * Check if the RDV is in the past.
     */
    public function getIsPastAttribute(): bool
    {
        return $this->date->isPast();
    }

    /**
     * Check if the RDV is upcoming (future date with planned status).
     */
    public function getIsUpcomingAttribute(): bool
    {
        return !$this->is_past && $this->statut === self::STATUS_PLANNED;
    }

    /**
     * Get the duration of the RDV in minutes.
     * Assuming a default duration of 30 minutes unless stored in the model.
     */
    public function getDurationAttribute(): int
    {
        return $this->attributes['duration'] ?? 30;
    }

    // ============================
    // Business Logic Methods
    // ============================

    /**
     * Check if the RDV can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->statut, [self::STATUS_PLANNED, self::STATUS_CONFIRMED])
            && !$this->is_past;
    }

    /**
     * Check if a devis can be created for this RDV.
     */
    public function canCreateDevis(): bool
    {
        return $this->statut === self::STATUS_COMPLETED && !$this->devis;
    }

    /**
     * Mark the RDV as completed.
     */
    public function markAsCompleted(): bool
    {
        if ($this->statut !== self::STATUS_CONFIRMED) {
            return false;
        }

        $this->statut = self::STATUS_COMPLETED;
        return $this->save();
    }

    /**
     * Determine if the user can view any RDV.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(['Account Manager', 'Freelancer', 'Admin', 'Super Admin']);
    }

    /**
     * Get paginated RDVs based on user role.
     */
    public static function getPaginatedRdvs(User $user)
    {
        return self::query()
            ->when($user->hasRole('Freelancer'), function ($query) use ($user) {
                $query->where('freelancer_id', $user->id);
            })
            ->when($user->hasRole('Account Manager'), function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })
            ->orWhere(function ($query) use ($user) {
                $query->where('freelancer_id', $user->id)
                      ->orWhere('manager_id', $user->id);
            })
            ->paginate(10);
    }
    
}
