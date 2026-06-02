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
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->date('blocked_date')->unique();
            $table->string('reason')->nullable();
            $table->enum('type', ['blocked', 'maintenance', 'holiday'])->default('blocked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_dates');
    }
};
