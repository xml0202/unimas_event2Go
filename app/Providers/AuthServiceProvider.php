<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\AgencyUser;
use App\Policies\UserPolicy;
use App\Policies\AgencyUserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        AgencyUser::class => AgencyUserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
        $this->registerPolicies();
        
        Gate::before(function ($user, $ability) {
            return $user->hasRole(['Super Admin']) ? true : null;
        });
    }
}
