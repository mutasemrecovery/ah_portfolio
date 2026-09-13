<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value_en', 'value_ar'];

    public static function get(string $key, string $locale = null): ?string
    {
        $locale = $locale ?? app()->getLocale();
        $col    = 'value_' . $locale;
        $row    = static::where('key', $key)->first();
        return $row?->$col;
    }

    public static function set(string $key, string $valueEn, string $valueAr = null): void
    {
        static::updateOrCreate(['key' => $key], [
            'value_en' => $valueEn,
            'value_ar' => $valueAr ?? $valueEn,
        ]);
    }
}
