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
            'settings'           => 'required|array',
            'settings.*.value_en' => 'nullable|string|max:1000',
            'settings.*.value_ar' => 'nullable|string|max:1000',
        ]);

        foreach ($request->settings as $key => $values) {
            SiteSetting::updateOrCreate(['key' => $key], [
                'value_en' => $values['value_en'] ?? null,
                'value_ar' => $values['value_ar'] ?? null,
            ]);
        }

        return back()->with('success', 'تم حفظ الإعدادات بنجاح.');
    }
}
