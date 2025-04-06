<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Rdv;
use App\Policies\ContactPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\RdvPolicy;


class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Contact::class => ContactPolicy::class,
        Rdv::class => RdvPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
