<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyMedia extends Model
{
    use HasFactory;

    protected $table = 'agency_media';

    protected $fillable = [
        'agency_id', 'type', 'file_path', 'thumbnail',
        'title_en', 'title_ar', 'url',
        'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function getTitleAttribute(): ?string
    {
        $col = 'title_' . app()->getLocale();
        return $this->$col ?? $this->title_en;
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset($this->file_path) : null;
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->thumbnail ? asset($this->thumbnail) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
