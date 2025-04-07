<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any contacts.
     */
    public function viewAny(User $user)
    {
        return true; // Allow all authenticated users to view contacts
    }


    /**
     * Determine whether the user can view a contact.
     */
    public function view(User $user, Contact $contact)
    {
        // User can only view their own contacts
        return $user->id === $contact->freelancer_id;
    }

    /**
     * Determine whether the user can create contacts.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('manage contacts');
    }

    /**
     * Determine whether the user can update the contact.
     */
    public function update(User $user, Contact $contact)
    {
        return $user->hasPermissionTo('manage contacts') && $user->id === $contact->freelancer_id;
    }

    /**
     * Determine whether the user can delete the contact.
     */
    public function delete(User $user, Contact $contact)
    {
        return $user->hasPermissionTo('manage contacts') && $user->id === $contact->freelancer_id;
    }

    /**
     * Determine whether the user can restore the contact.
     */
    public function restore(User $user, Contact $contact)
    {
        return $user->hasPermissionTo('manage contacts') && $user->id === $contact->freelancer_id;
    }
    
}
