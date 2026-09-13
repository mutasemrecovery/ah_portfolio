<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyService;
use Illuminate\Http\Request;

class AgencyServiceController extends Controller
{
    public function index(Agency $agency)
    {
        $services = $agency->services()->orderBy('sort_order')->get();
        return view('admin.agency_services.index', compact('agency', 'services'));
    }

    public function create(Agency $agency)
    {
        return view('admin.agency_services.create', compact('agency'));
    }

    public function store(Request $request, Agency $agency)
    {
        $request->validate([
            'title_en'   => 'required|string|max:300',
            'title_ar'   => 'required|string|max:300',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $agency->services()->create([
            'title_en'   => $request->title_en,
            'title_ar'   => $request->title_ar,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.agencies.services.index', $agency)->with('success', 'تم إضافة الخدمة بنجاح.');
    }

    public function edit(Agency $agency, AgencyService $service)
    {
        return view('admin.agency_services.edit', compact('agency', 'service'));
    }

    public function update(Request $request, Agency $agency, AgencyService $service)
    {
        $request->validate([
            'title_en'   => 'required|string|max:300',
            'title_ar'   => 'required|string|max:300',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $service->update([
            'title_en'   => $request->title_en,
            'title_ar'   => $request->title_ar,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.agencies.services.index', $agency)->with('success', 'تم تحديث الخدمة بنجاح.');
    }

    public function destroy(Agency $agency, AgencyService $service)
    {
        $service->delete();
        return back()->with('success', 'تم حذف الخدمة.');
    }
}
