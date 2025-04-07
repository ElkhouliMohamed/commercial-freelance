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

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $appends = [
        'formatted_date',
        'is_past',
        'is_upcoming',
    ];

    public const STATUS_PLANNED = 'planifié';
    public const STATUS_CONFIRMED = 'confirmé';
    public const STATUS_CANCELLED = 'annulé';
    public const STATUS_COMPLETED = 'terminé';

    public const TYPE_PHYSICAL = 'physique';
    public const TYPE_VIRTUAL = 'virtuel';
    public const TYPE_PHONE = 'téléphonique';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PLANNED,
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
            self::STATUS_COMPLETED,
        ];
    }

    public static function getTypeOptions(): array
    {
        return [
            self::TYPE_PHYSICAL,
            self::TYPE_VIRTUAL,
            self::TYPE_PHONE,
        ];
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function freelancer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function devis(): HasOne
    {
        return $this->hasOne(Devis::class);
    }

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'rdv_plan', 'rdv_id', 'plan_id')
            ->withTimestamps();
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('statut', $status);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByFreelancer($query, int $freelancerId)
    {
        return $query->where('freelancer_id', $freelancerId);
    }

    public function scopeByManager($query, int $managerId)
    {
        return $query->where('manager_id', $managerId);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now())
            ->where('statut', self::STATUS_PLANNED);
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('d/m/Y H:i');
    }

    public function getIsPastAttribute(): bool
    {
        return $this->date->isPast();
    }

    public function getIsUpcomingAttribute(): bool
    {
        return !$this->is_past && $this->statut === self::STATUS_PLANNED;
    }

    public function getDurationAttribute(): int
    {
        return $this->attributes['duration'] ?? 30;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->statut, [self::STATUS_PLANNED, self::STATUS_CONFIRMED])
            && !$this->is_past;
    }

    public function canCreateDevis(): bool
    {
        return $this->statut === self::STATUS_COMPLETED && !$this->devis;
    }

    public function markAsCompleted(): bool
    {
        if ($this->statut !== self::STATUS_CONFIRMED) {
            return false;
        }

        $this->statut = self::STATUS_COMPLETED;
        return $this->save();
    }

    public function viewAny(User $user)
    {
        return $user->hasRole(['Account Manager', 'Freelancer', 'Admin', 'Super Admin']);
    }

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
