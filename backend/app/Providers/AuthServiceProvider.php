<?php

namespace App\Providers;

use App\Models\CarBrand;
use App\Models\FavoriteCar;
use App\Models\User;
use App\Models\UserRequest;
use App\Policies\CarBrandPolicy;
use App\Policies\FavoriteCarPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class        => UserPolicy::class,
        FavoriteCar::class => FavoriteCarPolicy::class,
        CarBrand::class    => CarBrandPolicy::class,
        UserRequest::class => UserRequestPolicy::class,
    ];

    public function boot(): void
    {
        // A Gate automatikusan a $policies alapján regisztrál
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}

