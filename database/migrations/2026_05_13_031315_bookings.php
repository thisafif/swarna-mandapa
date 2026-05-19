<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();

            // Tanggal & tamu
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedTinyInteger('guests');

            // Data pemesan
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('country', 5)->default('ID');

            // Harga & promo
            $table->string('promo_code')->nullable();
            $table->decimal('price_per_night', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);

            // Status booking
            $table->enum('status', ['REQUESTED', 'PENDING', 'CONFIRMED', 'CANCELLED'])->default('REQUESTED');
            $table->boolean('is_manual')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();

            // Payment gateway
            $table->string('payment_order_id')->nullable(); // invoice number yang dikirim ke DOKU
            $table->string('payment_id')->nullable();       // transaction.id dari callback DOKU
            $table->string('payment_ref')->nullable();      // referensi tambahan jika diperlukan
            $table->timestamp('paid_at')->nullable();       // waktu pembayaran berhasil

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};