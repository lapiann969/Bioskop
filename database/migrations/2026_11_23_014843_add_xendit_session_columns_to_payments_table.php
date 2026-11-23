<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_session_id')
                ->nullable()
                ->after('payment_url');

            $table->string('payment_request_id')
                ->nullable()
                ->after('payment_session_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'payment_session_id',
                'payment_request_id',
            ]);
        });
    }
};
