<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutSection;
use Illuminate\Http\Request;

class AboutSectionController extends Controller
{
    public function edit()
    {
        $about = AboutSection::firstOrNew(['id' => 1]);
        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title_en' => 'required|string|max:200',
            'title_ar' => 'required|string|max:200',
            'body_en'  => 'required|string',
            'body_ar'  => 'required|string',
            'image'    => 'nullable|image|max:3072',
        ]);

        $data = $request->only(['title_en', 'title_ar', 'body_en', 'body_ar']);
        $data['is_active'] = true;

        if ($request->hasFile('image')) {
            $data['image'] = 'uploads/about/' . uploadImage('uploads/about', $request->file('image'));
        }

        AboutSection::updateOrCreate(['id' => 1], $data);

        return back()->with('success', 'تم تحديث قسم About بنجاح.');
    }
}
