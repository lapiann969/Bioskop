<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $guarded = ['id'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class); // Relasi ke pivot film_genre
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}