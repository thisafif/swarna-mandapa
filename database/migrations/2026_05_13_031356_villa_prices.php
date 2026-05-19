<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villa_prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('price_per_night', 12, 2);
            $table->date('valid_from');
            $table->date('valid_until')->nullable(); // null = berlaku selamanya
            $table->string('label')->nullable();     // e.g. "High Season", "Normal"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('villa_prices');
    }
};