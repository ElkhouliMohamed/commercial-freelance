<?php

namespace App\Policies;

use App\Models\Rdv;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RdvPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any RDVs.
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(['Super Admin', 'Account Manager', 'Freelancer', 'Admin']);
    }

    /**
     * Determine whether the user can view the RDV.
     */
    public function view(User $user, Rdv $rdv)
    {
        return $user->hasRole(['Super Admin', 'Account Manager', 'Admin']) ||
            $user->id === $rdv->freelancer_id ||
            $user->id === $rdv->manager_id;
    }

    /**
     * Determine whether the user can create RDVs.
     */
    public function create(User $user)
    {
        return $user->hasRole(['Freelancer', 'Account Manager', 'Super Admin', 'Admin']);
    }

    /**
     * Determine whether the user can update the RDV.
     */
    public function update(User $user, Rdv $rdv)
    {
        return $user->hasRole(['Super Admin', 'Account Manager', 'Admin']) ||
            ($user->hasRole('Freelancer') && (
                $rdv->freelancer_id === $user->id || // They created it
                $rdv->manager_id === $user->id        // They manage it
            ));
    }

    /**
     * Determine whether the user can delete the RDV.
     */
    public function delete(User $user, Rdv $rdv)
    {
        return $user->hasRole(['Super Admin', 'Account Manager', 'Admin']) ||
            ($user->hasRole('Freelancer') && (
                $rdv->freelancer_id === $user->id || // They created it
                $rdv->manager_id === $user->id        // They manage it
            ));
    }

    /**
     * Determine whether the user can restore the RDV.
     */
    public function restore(User $user, Rdv $rdv)
    {
        return $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can permanently delete the RDV.
     */
    public function forceDelete(User $user, Rdv $rdv)
    {
        return $user->hasRole('Super Admin');
    }
}
