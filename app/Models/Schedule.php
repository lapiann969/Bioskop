<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    // ==========================================
    // 👈 PERBAIKAN: 'show_date' ditambahkan di sini
    // ==========================================
    protected $fillable = [
        'film_id',
        'studio_id',
        'show_date', 
        'show_time',
        'price',
    ];

    protected function casts(): array
    {
        return [
            'show_date' => 'date',      // Tambahkan ini agar tanggal terbaca rapi
            'show_time' => 'datetime',
            'price' => 'decimal:2',
        ];
    }

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}