<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $policies = [
            'isAdmin' => 'admin',
            'isAuthor' => 'author',
            'isUser' => 'user',
            'isOrganization' => 'organization',
        ];

        foreach ($policies as $define => $type) {
            Gate::define($define, function($user) use ($type) {
                return $user->type === $type;
            });
        }

        Gate::define('isMyAccount', function($user, $profileUser) {
            return $user->id == $profileUser->id;
        });

        Passport::routes();
    }
}
