<?php

namespace App\Models;

use App\Models\Screening;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hall extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'capacity'
    ];

    // Relationship with Screenings
    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }
}
