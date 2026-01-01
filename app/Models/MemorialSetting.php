<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemorialSetting extends Model
{
    protected $fillable = [
        'mother_photo',
        'father_photo',
    ];

    /**
     * Get the singleton instance (there should only be one record).
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}
