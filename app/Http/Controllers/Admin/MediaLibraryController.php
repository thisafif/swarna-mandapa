<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryMedia;
use Illuminate\Http\Request;

class MediaLibraryController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->query('search');
        $type       = $request->query('type');
        $category   = $request->query('category');

        $query = GalleryMedia::orderBy('created_at', 'desc');

        if ($search) {
            $query->where('file_name', 'like', "%{$search}%");
        }
        if ($type) {
            $query->where('media_type', $type);
        }
        if ($category) {
            $query->where('category', $category);
        }

        $media      = $query->paginate(20)->withQueryString();
        $categories = GalleryMedia::$categories;
        $totalCount = GalleryMedia::count();
        $imageCount = GalleryMedia::where('media_type', 'image')->count();
        $videoCount = GalleryMedia::where('media_type', 'video')->count();

        return view('admin.media_library', compact(
            'media', 'categories', 'search', 'type', 'category',
            'totalCount', 'imageCount', 'videoCount'
        ));
    }
}
