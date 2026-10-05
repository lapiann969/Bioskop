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
    // Pastikan nama tabelnya 'genres' (pakai 's') ya agar sesuai standar Laravel
    Schema::create('genres', function (Blueprint $table) {
        $table->id(); // Ini otomatis membuat kolom 'id'
        $table->string('name'); // Ini untuk 'nama' genre
        $table->text('description')->nullable(); // Ini untuk 'deskripsi', nullable() artinya boleh dikosongkan
        $table->timestamps(); // Ini otomatis membuat kolom created_at dan updated_at
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
