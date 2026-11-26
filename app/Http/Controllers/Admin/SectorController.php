<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    public function index()
    {
        $sectors = Sector::orderBy('name')->get();
        return view('admin.sectors.index', compact('sectors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100|unique:sectors,name',
            'name_en' => 'nullable|string|max:100',
        ]);
        Sector::create(['name' => trim($request->name), 'name_en' => trim($request->name_en ?? '')]);
        return back()->with('success', 'تم إضافة القطاع.');
    }

    public function destroy(Sector $sector)
    {
        $sector->delete();
        return back()->with('success', 'تم حذف القطاع.');
    }
}
