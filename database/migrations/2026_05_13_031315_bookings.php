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
            $table->string('booking_code')->unique(); // Kode resi unik
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedTinyInteger('guests');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('country', 5)->default('ID');
            $table->string('promo_code')->nullable();
            $table->decimal('price_per_night', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->enum('status', ['REQUESTED', 'PENDING', 'CONFIRMED', 'CANCELLED'])->default('REQUESTED');
            $table->boolean('is_manual')->default(false); // FR-11: booking manual admin
            $table->string('payment_ref')->nullable();    // referensi dari payment gateway
            $table->timestamp('expires_at')->nullable();  // auto-cancel 1 jam
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};