<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en', 'title_ar',
        'subtitle_en', 'subtitle_ar',
        'cta_text_en', 'cta_text_ar',
        'cta_link', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function getTitleAttribute(): string
    {
        $col = 'title_' . app()->getLocale();
        return $this->$col ?? $this->title_en;
    }

    public function getSubtitleAttribute(): ?string
    {
        $col = 'subtitle_' . app()->getLocale();
        return $this->$col ?? $this->subtitle_en;
    }

    public function getCtaTextAttribute(): ?string
    {
        $col = 'cta_text_' . app()->getLocale();
        return $this->$col ?? $this->cta_text_en;
    }
}
