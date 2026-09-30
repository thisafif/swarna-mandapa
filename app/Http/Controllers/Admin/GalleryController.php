<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query    = GalleryMedia::orderBy('category')->orderBy('sort_order');

        if ($category) {
            $query->where('category', $category);
        }

        $media      = $query->paginate(24)->withQueryString();
        $categories = GalleryMedia::$categories;

        return view('admin.gallery.index', compact('media', 'categories', 'category'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'category'  => 'required|string|in:' . implode(',', array_keys(GalleryMedia::$categories)),
            'files'     => 'required|array|min:1|max:20',
            'files.*'   => 'required|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $uploaded = 0;

        foreach ($request->file('files') as $file) {
            $mediaType = 'image';
            $filename  = time() . '_' . $uploaded . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $path      = 'gallery/' . $request->category . '/' . $filename;

            Storage::disk('r2')->put($path, file_get_contents($file), 'public');
            $url = Storage::disk('r2')->url($path);

            GalleryMedia::where('category', $request->category)->increment('sort_order');

            GalleryMedia::create([
                'category'     => $request->category,
                'file_name'    => $file->getClientOriginalName(),
                'file_url'     => $url,
                'media_type'   => $mediaType,
                'disk'         => 'r2',
                'storage_path' => $path,
                'sort_order'   => 1,
            ]);

            $uploaded++;
        }

        return redirect()->route('admin.gallery.index', ['category' => $request->category])
            ->with('success', "{$uploaded} file(s) uploaded successfully!");
    }

    public function edit(GalleryMedia $gallery)
    {
        $categories = GalleryMedia::$categories;
        return view('admin.gallery.edit', compact('gallery', 'categories'));
    }

    public function update(Request $request, GalleryMedia $gallery)
    {
        $request->validate([
            'category'   => 'required|string|in:' . implode(',', array_keys(GalleryMedia::$categories)),
            'sort_order' => 'required|integer|min:0',
            'file_name'  => 'required|string|max:255',
        ]);

        $gallery->update([
            'category'   => $request->category,
            'sort_order' => $request->sort_order,
            'file_name'  => $request->file_name,
        ]);

        return redirect()->route('admin.gallery.index', ['category' => $gallery->category])
            ->with('success', 'Media updated successfully!');
    }

    public function destroy(GalleryMedia $gallery)
    {
        if ($gallery->storage_path) {
            Storage::disk('r2')->delete($gallery->storage_path);
        }

        $gallery->delete();

        return redirect()->back()->with('success', 'Media deleted successfully!');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:gallery_media,id',
        ]);

        foreach ($request->order as $sortOrder => $id) {
            GalleryMedia::where('id', $id)->update(['sort_order' => $sortOrder]);
        }

        return response()->json(['success' => true]);
    }
}