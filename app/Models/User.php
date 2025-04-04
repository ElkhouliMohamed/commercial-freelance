<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    // Specify the guard name for Spatie roles and permissions
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ============================
    // Relationships
    // ============================

    /**
     * A user (freelancer) has many contacts.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'freelancer_id');
    }

    /**
     * A user (freelancer) has many RDVs.
     */
    public function rdvs()
    {
        return $this->hasMany(Rdv::class, 'freelancer_id');
    }

    /**
     * A user (account manager) manages many RDVs.
     */
    public function managedRdvs()
    {
        return $this->hasMany(Rdv::class, 'manager_id');
    }

    /**
     * A user (freelancer) has many commissions.
     */
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'freelancer_id');
    }

    /**
     * A user (freelancer) has one abonnement.
     */
    public function abonnement()
    {
        return $this->hasOne(Abonnement::class, 'freelancer_id');
    }

    // ============================
    // Scopes
    // ============================

    /**
     * Scope: Filter users by role.
     */
    public function scopeByRole($query, $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    // ============================
    // Accessors
    // ============================

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        return $this->name; // Update if you have separate `first_name` and `last_name` fields.
    }

    // ============================
    // Utility Methods
    // ============================

    /**
     * Check if the user has a specific role.
     */
    public function hasRoleName($role)
    {
        return $this->hasRole($role);
    }

    /**
     * Assign a role to the user.
     */
    public function assignUserRole($role)
    {
        $this->assignRole($role);
    }
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
    }
}
