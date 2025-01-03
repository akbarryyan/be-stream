<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'genre', 'release_year', 'duration', 'video_url'
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'film_artist');
    }
}

