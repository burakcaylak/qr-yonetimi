<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;


// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Model::class => Policy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

            Gate::define('product-delete', function ($user) {
                // Spatie Permission varsa:
                if (method_exists($user, 'hasRole')) {
                    return $user->hasRole('admin');
                }
                // Yedek kontrol: users tablosunda 'role' kolonu varsa
                return ($user->role ?? null) === 'admin';
            });
    }
}
