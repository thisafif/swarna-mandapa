<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryMedia extends Model
{
    protected $fillable = [
        'category',
        'file_name',
        'file_url',
        'media_type',
        'disk',
        'storage_path',
        'sort_order',
    ];

    public static array $categories = [
        'grand-living-spaces'   => 'Grand Living Spaces',
        'grand-master-suite'    => 'Grand Master Suite',
        'master-guest-suite'    => 'Master Guest Suite',
        'guest-suite'           => 'Guest Suite',
        'outdoor-elegance'      => 'Outdoor Elegance',
        'the-heart-of-the-home' => 'The Heart of the Home',
    ];

    public static function categoryLabel(string $slug): string
    {
        return static::$categories[$slug] ?? ucwords(str_replace('-', ' ', $slug));
    }

    /**
     * Get all media grouped by category, ordered by sort_order.
     */
    public static function grouped(): array
    {
        return static::orderBy('sort_order')->get()
            ->groupBy('category')
            ->toArray();
    }

    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }
}
