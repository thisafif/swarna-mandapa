<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_contents', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();        // e.g. hero_title, hero_subtitle, hero_button_text, hero_image_url, hero_video_url
            $table->text('value')->nullable();      // The content value
            $table->string('type')->default('text'); // text | url
            $table->string('label')->nullable();    // Human-readable label for admin UI
            $table->timestamps();
        });

        // Seed default values (fallback to current hardcoded content)
        DB::table('home_contents')->insert([
            [
                'key'        => 'hero_title',
                'value'      => 'Swarna Mandapa',
                'type'       => 'text',
                'label'      => 'Hero Title',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'hero_subtitle',
                'value'      => 'A golden sanctuary where tradition and luxury live in perfect harmony.',
                'type'       => 'text',
                'label'      => 'Hero Subtitle',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'hero_button_text',
                'value'      => 'Check Availability →',
                'type'       => 'text',
                'label'      => 'Hero Button Text',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'hero_video_url',
                'value'      => null,
                'type'       => 'url',
                'label'      => 'Hero Background Video URL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key'        => 'hero_image_url',
                'value'      => null,
                'type'       => 'url',
                'label'      => 'Hero Background Image URL (fallback if no video)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('home_contents');
    }
};
