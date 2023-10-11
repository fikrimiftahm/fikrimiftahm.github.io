<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repository\AuthenticationRepositoryInterface;
use App\Repository\Eloquent\AuthenticationRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthenticationRepositoryInterface::class, AuthenticationRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
