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
    Schema::create('studios', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Untuk nama studio (contoh: Studio 1)
        $table->integer('capacity'); // Untuk jumlah kapasitas kursi
        
        // Menggunakan enum agar status lebih terstruktur dan aman
        $table->enum('status', ['Active', 'Maintenance'])->default('Active'); 
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};
