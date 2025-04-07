<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\Rdv;
use App\Policies\ContactPolicy;
use App\Policies\RdvPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Contact::class => ContactPolicy::class,
        Rdv::class => RdvPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
        Paginator::useTailwind();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
