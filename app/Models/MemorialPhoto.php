<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemorialPhoto extends Model
{
    protected $fillable = [
        'photo_path',
        'caption',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get active photos ordered by sort_order
     */
    public static function getActivePhotos()
    {
        return static::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
