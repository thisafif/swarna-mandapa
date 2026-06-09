<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeContentController extends Controller
{
    public function index()
    {
        $content = HomeContent::all()->keyBy('key');
        return view('admin.home_content', compact('content'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_title'        => 'nullable|string|max:255',
            'hero_subtitle'     => 'nullable|string|max:500',
            'hero_button_text'  => 'nullable|string|max:100',
            'hero_video'        => 'nullable|file|mimes:mp4,mov,avi,webm|max:204800',
            'hero_image'        => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',

            // About
            'about_heritage_img' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'about_garden_img'   => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',

            // Amenities
            'amenities_img'     => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',

            // Gallery preview
            'gallery_living_img'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery_room_img'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery_door_img'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery_kitchen_img' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery_statue_img'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery_pool_img'    => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',

            // Suites
            'master_suite_img'  => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'grand_suite_img'   => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'guest_suite_1_img' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'guest_suite_2_img' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'guest_suite_3_img' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        // ── Text fields ────────────────────────────────────────────────────────
        HomeContent::set('hero_title',       $request->input('hero_title'));
        HomeContent::set('hero_subtitle',    $request->input('hero_subtitle'));
        HomeContent::set('hero_button_text', $request->input('hero_button_text'));

        // ── File upload map: input_name => [db_key, folder] ───────────────────
        $uploads = [
            'hero_video'         => ['hero_video_url',    'hero'],
            'hero_image'         => ['hero_image_url',    'hero'],
            'about_heritage_img' => ['about_heritage_img', 'about'],
            'about_garden_img'   => ['about_garden_img',   'about'],
            'amenities_img'      => ['amenities_img',      'amenities'],
            'gallery_living_img' => ['gallery_living_img', 'gallery'],
            'gallery_room_img'   => ['gallery_room_img',   'gallery'],
            'gallery_door_img'   => ['gallery_door_img',   'gallery'],
            'gallery_kitchen_img'=> ['gallery_kitchen_img','gallery'],
            'gallery_statue_img' => ['gallery_statue_img', 'gallery'],
            'gallery_pool_img'   => ['gallery_pool_img',   'gallery'],
            'master_suite_img'   => ['master_suite_img',   'suites'],
            'grand_suite_img'    => ['grand_suite_img',    'suites'],
            'guest_suite_1_img'  => ['guest_suite_1_img',  'suites'],
            'guest_suite_2_img'  => ['guest_suite_2_img',  'suites'],
            'guest_suite_3_img'  => ['guest_suite_3_img',  'suites'],
        ];

        foreach ($uploads as $inputName => [$dbKey, $folder]) {
            if ($request->hasFile($inputName)) {
                $url = $this->uploadToR2($request->file($inputName), $folder);
                HomeContent::set($dbKey, $url);
            }
        }

        return redirect()->route('admin.home_content.index')
            ->with('success', 'Home content updated successfully!');
    }

    private function uploadToR2($file, string $folder): string
    {
        $filename = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $path     = $folder . '/' . $filename;

        Storage::disk('r2')->put($path, file_get_contents($file), 'public');

        return Storage::disk('r2')->url($path);
    }
}
