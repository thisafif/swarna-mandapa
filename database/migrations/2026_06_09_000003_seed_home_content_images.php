<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $entries = [
            // About section
            ['key' => 'about_heritage_img', 'label' => 'About — Heritage Photo',     'type' => 'url'],
            ['key' => 'about_garden_img',   'label' => 'About — Garden Photo',       'type' => 'url'],

            // Amenities section
            ['key' => 'amenities_img',      'label' => 'Amenities — Side Photo',     'type' => 'url'],

            // Gallery preview (homepage)
            ['key' => 'gallery_living_img', 'label' => 'Gallery Preview — Living Room (large)', 'type' => 'url'],
            ['key' => 'gallery_room_img',   'label' => 'Gallery Preview — Guest Bedroom',       'type' => 'url'],
            ['key' => 'gallery_door_img',   'label' => 'Gallery Preview — Carved Door',         'type' => 'url'],
            ['key' => 'gallery_kitchen_img','label' => 'Gallery Preview — Kitchen',             'type' => 'url'],
            ['key' => 'gallery_statue_img', 'label' => 'Gallery Preview — Statue',              'type' => 'url'],
            ['key' => 'gallery_pool_img',   'label' => 'Gallery Preview — Pool',               'type' => 'url'],

            // Suites
            ['key' => 'master_suite_img',   'label' => 'Suite — Grand Master Suite Photo',  'type' => 'url'],
            ['key' => 'grand_suite_img',    'label' => 'Suite — Master Guest Suite Photo',  'type' => 'url'],
            ['key' => 'guest_suite_1_img',  'label' => 'Suite — Guest Suite 1 Photo',       'type' => 'url'],
            ['key' => 'guest_suite_2_img',  'label' => 'Suite — Guest Suite 2 Photo',       'type' => 'url'],
            ['key' => 'guest_suite_3_img',  'label' => 'Suite — Guest Suite 3 Photo',       'type' => 'url'],
        ];

        foreach ($entries as $entry) {
            // Skip if key already exists
            if (DB::table('home_contents')->where('key', $entry['key'])->exists()) {
                continue;
            }

            DB::table('home_contents')->insert([
                'key'        => $entry['key'],
                'value'      => null, // blank → blade falls back to local asset()
                'type'       => $entry['type'],
                'label'      => $entry['label'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        $keys = [
            'about_heritage_img','about_garden_img','amenities_img',
            'gallery_living_img','gallery_room_img','gallery_door_img',
            'gallery_kitchen_img','gallery_statue_img','gallery_pool_img',
            'master_suite_img','grand_suite_img',
            'guest_suite_1_img','guest_suite_2_img','guest_suite_3_img',
        ];

        DB::table('home_contents')->whereIn('key', $keys)->delete();
    }
};
