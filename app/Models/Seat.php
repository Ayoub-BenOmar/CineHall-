<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'hall_id', 'seat_number', 'is_reserved'
    ];

    // Relationship with Hall
    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }
}
