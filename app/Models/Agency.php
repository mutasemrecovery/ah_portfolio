<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en', 'name_ar', 'slug', 'logo',
        'description_en', 'description_ar',
        'heading_en', 'heading_ar',
        'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function services()
    {
        return $this->hasMany(AgencyService::class)->orderBy('sort_order');
    }

    public function media()
    {
        return $this->hasMany(AgencyMedia::class)->orderBy('sort_order');
    }

    public function getNameAttribute(): string
    {
        $col = 'name_' . app()->getLocale();
        return $this->$col ?? $this->name_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        $col = 'description_' . app()->getLocale();
        return $this->$col ?? $this->description_en;
    }

    public function getHeadingAttribute(): ?string
    {
        $col = 'heading_' . app()->getLocale();
        return $this->$col ?? $this->heading_en;
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset($this->logo) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
