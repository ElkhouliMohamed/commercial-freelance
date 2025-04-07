<?php

namespace App\Policies;

use App\Models\Devis;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DevisPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasRole(['Super Admin', 'Account Manager', 'Freelancer', 'Admin']);
    }

    public function view(User $user, Devis $devis)
    {
        return $user->hasRole(['Super Admin', 'Account Manager', 'Admin']) ||
            $user->id === $devis->freelancer_id;
    }

    public function create(User $user)
    {
        return $user->hasRole('Account Manager'); // Only Account Managers can create
    }

    public function update(User $user, Devis $devis)
    {
        return $user->hasRole(['Super Admin', 'Account Manager', 'Admin']) ||
            $user->id === $devis->freelancer_id;
    }

    public function delete(User $user, Devis $devis)
    {
        return $user->hasRole(['Super Admin', 'Account Manager', 'Admin']) ||
            $user->id === $devis->freelancer_id;
    }
}
