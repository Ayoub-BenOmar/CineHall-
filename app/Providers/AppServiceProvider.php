<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\MovieRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\MovieRepositoryInterface;
use App\Repositories\ScreeningRepository;
use App\Repositories\ScreeningRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            MovieRepositoryInterface::class,
            MovieRepository::class
        );

        $this->app->bind(
            ScreeningRepositoryInterface::class,
            ScreeningRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
