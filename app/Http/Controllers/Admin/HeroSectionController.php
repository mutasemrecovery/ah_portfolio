<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;

class HeroSectionController extends Controller
{
    public function edit()
    {
        $hero = HeroSection::firstOrNew(['id' => 1]);
        return view('admin.hero.edit', compact('hero'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title_en'    => 'required|string|max:200',
            'title_ar'    => 'required|string|max:200',
            'subtitle_en' => 'nullable|string|max:300',
            'subtitle_ar' => 'nullable|string|max:300',
            'cta_text_en' => 'nullable|string|max:100',
            'cta_text_ar' => 'nullable|string|max:100',
            'cta_link'    => 'nullable|string|max:200',
        ]);

        HeroSection::updateOrCreate(['id' => 1], [
            'title_en'    => $request->title_en,
            'title_ar'    => $request->title_ar,
            'subtitle_en' => $request->subtitle_en,
            'subtitle_ar' => $request->subtitle_ar,
            'cta_text_en' => $request->cta_text_en,
            'cta_text_ar' => $request->cta_text_ar,
            'cta_link'    => $request->cta_link,
            'is_active'   => true,
        ]);

        return back()->with('success', 'تم تحديث قسم Hero بنجاح.');
    }
}
