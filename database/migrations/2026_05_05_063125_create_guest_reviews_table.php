<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('guest_reviews', function (Blueprint $table) {
        $table->id();
        // Ganti 'name' menjadi dua kolom di bawah ini
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email'); // Pastikan email juga ada karena divalidasi di Controller
        $table->text('review')->nullable();
        $table->integer('rating')->default(5);
        $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('guest_reviews');
    }
};