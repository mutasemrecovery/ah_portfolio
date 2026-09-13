<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en', 'title_ar',
        'body_en', 'body_ar',
        'image', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function getTitleAttribute(): string
    {
        $col = 'title_' . app()->getLocale();
        return $this->$col ?? $this->title_en;
    }

    public function getBodyAttribute(): string
    {
        $col = 'body_' . app()->getLocale();
        return $this->$col ?? $this->body_en;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset($this->image) : null;
    }
}
