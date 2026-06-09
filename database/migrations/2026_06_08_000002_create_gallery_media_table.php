<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_media', function (Blueprint $table) {
            $table->id();
            $table->string('category');             // grand-living-spaces, grand-master-suite, etc.
            $table->string('file_name');            // original filename, e.g. living-room-01.jpg
            $table->text('file_url');               // full public URL (Cloudflare R2 or fallback)
            $table->string('media_type')->default('image'); // image | video
            $table->string('disk')->default('r2');  // which storage disk was used
            $table->string('storage_path')->nullable(); // path within the bucket/disk
            $table->unsignedInteger('sort_order')->default(0); // for ordering within category
            $table->timestamps();

            $table->index('category');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_media');
    }
};
