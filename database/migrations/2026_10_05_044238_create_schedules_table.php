<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('film_id')->constrained()->cascadeOnDelete();
        $table->foreignId('studio_id')->constrained()->cascadeOnDelete();
        $table->dateTime('show_time'); // Tanggal & jam tayang
        $table->decimal('price', 10, 2); // Harga tiket jadwal ini
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
