<?php

namespace App\Providers;

use App\Repositories\HallRepository;
use App\Repositories\SeatRepository;
use App\Repositories\UserRepository;
use App\Repositories\MovieRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ScreeningRepository;
use App\Repositories\HallRepositoryInterface;
use App\Repositories\SeatRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\MovieRepositoryInterface;
use App\Repositories\ReservationRepository;
use App\Repositories\ReservationRepositoryInterface;
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

        $this->app->bind(
            HallRepositoryInterface::class,
            HallRepository::class
        );

        $this->app->bind(
            SeatRepositoryInterface::class,
            SeatRepository::class
        );

        $this->app->bind(
            ReservationRepositoryInterface::class,
            ReservationRepository::class
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
