<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeContent extends Model
{
    protected $fillable = ['key', 'value', 'type', 'label'];

    /**
     * Get a content value by key, with optional fallback.
     */
    public static function get(string $key, string $fallback = ''): string
    {
        $record = static::where('key', $key)->first();
        return $record?->value ?? $fallback;
    }

    /**
     * Set a content value by key (upsert).
     */
    public static function set(string $key, ?string $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
    }

    /**
     * Return all content as a keyed array.
     */
    public static function allKeyed(): array
    {
        return static::all()->pluck('value', 'key')->toArray();
    }
}
