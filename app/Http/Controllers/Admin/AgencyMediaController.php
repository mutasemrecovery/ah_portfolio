<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyMedia;
use Illuminate\Http\Request;

class AgencyMediaController extends Controller
{
    public function index(Agency $agency)
    {
        $media = $agency->media()->orderBy('type')->orderBy('sort_order')->get();
        return view('admin.agency_media.index', compact('agency', 'media'));
    }

    public function create(Agency $agency)
    {
        return view('admin.agency_media.create', compact('agency'));
    }

    public function store(Request $request, Agency $agency)
    {
        $request->validate([
            'type'       => 'required|in:video,image,website',
            'title_en'   => 'nullable|string|max:300',
            'title_ar'   => 'nullable|string|max:300',
            'url'        => 'nullable|string|max:500',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480',
            'thumbnail'  => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'type'       => $request->type,
            'title_en'   => $request->title_en,
            'title_ar'   => $request->title_ar,
            'url'        => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = 'uploads/agency_media/' . uploadImage('uploads/agency_media', $request->file('file'));
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = 'uploads/agency_media/thumbs/' . uploadImage('uploads/agency_media/thumbs', $request->file('thumbnail'));
        }

        $agency->media()->create($data);

        return redirect()->route('admin.agencies.media.index', $agency)->with('success', 'تم إضافة الوسيط بنجاح.');
    }

    public function edit(Agency $agency, AgencyMedia $medium)
    {
        return view('admin.agency_media.edit', compact('agency', 'medium'));
    }

    public function update(Request $request, Agency $agency, AgencyMedia $medium)
    {
        $request->validate([
            'type'       => 'required|in:video,image,website',
            'title_en'   => 'nullable|string|max:300',
            'title_ar'   => 'nullable|string|max:300',
            'url'        => 'nullable|string|max:500',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480',
            'thumbnail'  => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'type'       => $request->type,
            'title_en'   => $request->title_en,
            'title_ar'   => $request->title_ar,
            'url'        => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = 'uploads/agency_media/' . uploadImage('uploads/agency_media', $request->file('file'));
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = 'uploads/agency_media/thumbs/' . uploadImage('uploads/agency_media/thumbs', $request->file('thumbnail'));
        }

        $medium->update($data);

        return redirect()->route('admin.agencies.media.index', $agency)->with('success', 'تم تحديث الوسيط بنجاح.');
    }

    public function destroy(Agency $agency, AgencyMedia $medium)
    {
        $medium->delete();
        return back()->with('success', 'تم حذف الوسيط.');
    }
}
