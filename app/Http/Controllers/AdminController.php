<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Movie;
use App\Models\Screening;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Ticket;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin');
    }

    public function getDashboardStats()
    {
        $stats = [
            'total_movies' => Movie::count(),
            'total_screenings' => Screening::count(),
            'total_reservations' => Reservation::count(),
            'total_tickets_sold' => Ticket::where('status', 'active')->count(),
            'total_revenue' => Reservation::where('status', 'confirmed')
                ->sum('total_amount'),
            'total_users' => User::count(),
        ];

        return response()->json($stats);
    }

    public function getMovieStats()
    {
        $movieStats = Movie::withCount(['screenings', 'reservations'])
            ->withSum('reservations', 'total_amount')
            ->orderBy('reservations_count', 'desc')
            ->get()
            ->map(function ($movie) {
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'total_screenings' => $movie->screenings_count,
                    'total_tickets_sold' => $movie->reservations_count,
                    'total_revenue' => $movie->reservations_sum_total_amount,
                    'occupancy_rate' => $this->calculateOccupancyRate($movie)
                ];
            });

        return response()->json($movieStats);
    }

    public function getScreeningStats()
    {
        $screeningStats = Screening::withCount('reservations')
            ->withSum('reservations', 'total_amount')
            ->orderBy('start_time', 'desc')
            ->get()
            ->map(function ($screening) {
                return [
                    'id' => $screening->id,
                    'movie_title' => $screening->movie->title,
                    'start_time' => $screening->start_time,
                    'total_tickets_sold' => $screening->reservations_count,
                    'total_revenue' => $screening->reservations_sum_total_amount,
                    'occupancy_rate' => $this->calculateScreeningOccupancyRate($screening)
                ];
            });

        return response()->json($screeningStats);
    }

    public function getUserStats()
    {
        $userStats = User::withCount('reservations')
            ->withSum('reservations', 'total_amount')
            ->orderBy('reservations_count', 'desc')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'total_reservations' => $user->reservations_count,
                    'total_spent' => $user->reservations_sum_total_amount
                ];
            });

        return response()->json($userStats);
    }

    private function calculateOccupancyRate($movie)
    {
        $totalSeats = $movie->screenings->sum(function ($screening) {
            return $screening->hall->seats->count();
        });

        if ($totalSeats === 0) {
            return 0;
        }

        return ($movie->reservations_count / $totalSeats) * 100;
    }

    private function calculateScreeningOccupancyRate($screening)
    {
        $totalSeats = $screening->hall->seats->count();
        if ($totalSeats === 0) {
            return 0;
        }

        return ($screening->reservations_count / $totalSeats) * 100;
    }
} 