<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom payment ke tabel bookings
     * Jalankan: php artisan migrate
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Order ID unik yang dikirim ke DOKU
            $table->string('payment_order_id')->nullable()->after('status');

            // Transaction ID yang dikembalikan DOKU setelah sukses
            $table->string('payment_id')->nullable()->after('payment_order_id');

            // Waktu pembayaran berhasil
            $table->timestamp('paid_at')->nullable()->after('payment_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_order_id', 'payment_id', 'paid_at']);
        });
    }
};