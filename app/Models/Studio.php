<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $guarded = ['id'];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}