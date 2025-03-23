<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image', 'duration', 'minimum_age', 'trailer', 'genre', 'actors'
    ];

    protected $casts = [
        'actors' => 'array',
    ];

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }
}
