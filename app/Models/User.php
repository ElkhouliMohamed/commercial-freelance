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

    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function rdvs()
    {
        return $this->hasMany(Rdv::class, 'freelancer_id');
    }

    public function managedRdvs()
    {
        return $this->hasMany(Rdv::class, 'manager_id');
    }

    public function abonnement()
    {
        return $this->hasOne(Abonnement::class, 'freelancer_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'freelancer_id');
    }

    public function scopeByRole($query, $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function hasRoleName($role)
    {
        return $this->hasRole($role);
    }

    public function assignUserRole($role)
    {
        $this->assignRole($role);
    }
    public function commissions()
    {
        return $this->hasMany(Commission::class, 'freelancer_id');
    }
    public function devis()
    {
        return $this->hasMany(Devis::class, 'freelancer_id');
    }
}
