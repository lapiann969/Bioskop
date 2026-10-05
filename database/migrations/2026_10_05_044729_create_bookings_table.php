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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->string('booking_code')->unique(); // Kode booking (contoh: BKG-12345)
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
        
        $table->integer('total_tickets'); // TAMBAHAN: Menyimpan jumlah tiket yang dipesan
        
        $table->decimal('total_price', 10, 2); // Total harga keseluruhan
        $table->enum('status', ['Pending', 'Paid', 'Cancelled'])->default('Pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
