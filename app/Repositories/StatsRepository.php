<?php
namespace App\Repositories;

use App\Models\Movie;
use App\Models\Screening;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class StatsRepository implements StatsRepositoryInterface
{
    public function getTotalCounts(): array
    {
        return [
            'movies' => Movie::count(),
            'screenings' => Screening::count(),
            'reservations' => Reservation::count(),
            'users' => User::count()
        ];
    }
}



















