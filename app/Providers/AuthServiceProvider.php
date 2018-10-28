<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
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
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $policies = [
            'isAdmin' => 'admin',
            'isAuthor' => 'author',
            'isUser' => 'user',
            'isOrganization' => 'organization',
        ];

        foreach ($policies as $define => $type) {
            $gate->define($define, function($user) use ($type) {
                return $user->type === $type;
            });
        }

        $gate->define('isMyAccount', function($user, $profileUser) {
            return $user->id == $profileUser->id;
        });

        Passport::routes();
    }
}
