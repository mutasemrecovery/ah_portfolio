<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('key')->get()->keyBy('key');
        return view('admin.site_settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings'            => 'required|array',
            'settings.*.value_en' => 'nullable|string|max:1000',
            'settings.*.value_ar' => 'nullable|string|max:1000',
        ]);

        foreach ($request->settings as $key => $values) {
            // Image settings are managed separately via uploadImages()
            if (str_ends_with($key, '_image')) continue;

            SiteSetting::updateOrCreate(['key' => $key], [
                'value_en' => $values['value_en'] ?? null,
                'value_ar' => $values['value_ar'] ?? null,
            ]);
        }

        return back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
        ]);

        $uploaded = 0;
        foreach ($request->file('images', []) as $key => $file) {
            if (!$file || !$file->isValid()) continue;

            $path = 'uploads/site/' . uploadImage('uploads/site', $file);

            SiteSetting::updateOrCreate(['key' => $key], [
                'value_en' => $path,
                'value_ar' => $path,
            ]);
            $uploaded++;
        }

        return back()->with('success', "تم رفع {$uploaded} صورة بنجاح.");
    }
}
