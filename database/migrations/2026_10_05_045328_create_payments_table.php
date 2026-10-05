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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
        $table->string('payment_method')->nullable(); // Diisi nanti oleh webhook Xendit
        $table->decimal('amount', 10, 2);
        $table->enum('status', ['Pending', 'Success', 'Failed'])->default('Pending');
        $table->string('payment_url')->nullable(); // Menyimpan link Xendit untuk dibayar
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
