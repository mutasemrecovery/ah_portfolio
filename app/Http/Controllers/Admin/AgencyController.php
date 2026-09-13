<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies = Agency::withCount('services', 'media')->orderBy('sort_order')->get();
        return view('admin.agencies.index', compact('agencies'));
    }

    public function create()
    {
        return view('admin.agencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_en'        => 'required|string|max:200',
            'name_ar'        => 'required|string|max:200',
            'description_en' => 'nullable|string|max:500',
            'description_ar' => 'nullable|string|max:500',
            'heading_en'     => 'nullable|string|max:300',
            'heading_ar'     => 'nullable|string|max:300',
            'logo'           => 'nullable|image|max:2048',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name_en', 'name_ar', 'description_en', 'description_ar', 'heading_en', 'heading_ar', 'sort_order']);
        $data['slug']      = Str::slug($request->name_en);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $data['logo'] = 'uploads/agencies/' . uploadImage('uploads/agencies', $request->file('logo'));
        }

        Agency::create($data);

        return redirect()->route('admin.agencies.index')->with('success', 'تم إضافة الوكالة بنجاح.');
    }

    public function edit(Agency $agency)
    {
        $agency->load(['services' => fn($q) => $q->orderBy('sort_order'), 'media' => fn($q) => $q->orderBy('sort_order')]);
        return view('admin.agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency)
    {
        $request->validate([
            'name_en'        => 'required|string|max:200',
            'name_ar'        => 'required|string|max:200',
            'description_en' => 'nullable|string|max:500',
            'description_ar' => 'nullable|string|max:500',
            'heading_en'     => 'nullable|string|max:300',
            'heading_ar'     => 'nullable|string|max:300',
            'logo'           => 'nullable|image|max:2048',
            'sort_order'     => 'nullable|integer|min:0',
        ]);

        $data = $request->only(['name_en', 'name_ar', 'description_en', 'description_ar', 'heading_en', 'heading_ar', 'sort_order']);
        $data['slug']      = Str::slug($request->name_en);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('logo')) {
            $data['logo'] = 'uploads/agencies/' . uploadImage('uploads/agencies', $request->file('logo'));
        }

        $agency->update($data);

        return redirect()->route('admin.agencies.index')->with('success', 'تم تحديث الوكالة بنجاح.');
    }

    public function destroy(Agency $agency)
    {
        $agency->delete();
        return back()->with('success', 'تم حذف الوكالة.');
    }

    public function toggleActive(Agency $agency)
    {
        $agency->update(['is_active' => !$agency->is_active]);
        return back()->with('success', 'تم تحديث حالة الوكالة.');
    }
}
