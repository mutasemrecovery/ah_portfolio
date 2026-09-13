<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyService extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id', 'title_en', 'title_ar',
        'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function getTitleAttribute(): string
    {
        $col = 'title_' . app()->getLocale();
        return $this->$col ?? $this->title_en;
    }
}
