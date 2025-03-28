<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'screening_id', 'status', 'expires_at', 'seat_id'
    ];

    protected $casts = [
        // 'seat_id' => 'array',
        'expires_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    // public function payment()
    // {
    //     return $this->hasOne(Payment::class);
    // }
}
