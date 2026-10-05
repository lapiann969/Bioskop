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
    Schema::create('films', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable(); // Sinopsis dari TMDB
        $table->integer('duration'); // Durasi dalam menit
        $table->string('poster_url')->nullable(); // Link gambar dari TMDB
        $table->enum('status', ['Upcoming', 'Showing', 'Ended'])->default('Upcoming');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
