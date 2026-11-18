<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    protected $fillable = [
        'tmdb_id',
        'name',
        'description',
    ];

    public function films(): BelongsToMany
    {
        return $this->belongsToMany(Film::class)
            ->withTimestamps();
    }
}