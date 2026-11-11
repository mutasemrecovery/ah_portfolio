<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\AgencyMedia;
use App\Models\Sector;
use Illuminate\Http\Request;

class AgencyMediaController extends Controller
{
    private function sectors(): \Illuminate\Support\Collection
    {
        return Sector::orderBy('name')->pluck('name');
    }

    public function index(Agency $agency)
    {
        $media   = $agency->media()->orderByDesc('id')->get();
        $sectors = $this->sectors();
        return view('admin.agency_media.index', compact('agency', 'media', 'sectors'));
    }

    public function create(Agency $agency)
    {
        $sectors = $this->sectors();
        return view('admin.agency_media.create', compact('agency', 'sectors'));
    }

    public function store(Request $request, Agency $agency)
    {
        $request->validate([
            'type'        => 'required|in:video,image,visual_identity,website',
            'title_en'    => 'nullable|string|max:300',
            'title_ar'    => 'nullable|string|max:300',
            'url'         => 'nullable|string|max:500',
            'files'       => 'nullable|array',
            'files.*'     => 'file|mimes:jpg,jpeg,png,gif,mp4,webm,pdf|max:20480',
            'thumbnail'   => 'nullable|image|max:2048',
            'sort_order'  => 'nullable|integer|min:0',
            'sector'      => 'nullable|string|max:100',
        ]);

        $base = [
            'type'      => $request->type,
            'title_en'  => $request->title_en,
            'title_ar'  => $request->title_ar,
            'url'       => $request->url,
            'sector'    => $request->sector ?: null,
            'is_active' => $request->boolean('is_active', true),
        ];

        $thumbPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbPath = 'uploads/agency_media/thumbs/' . uploadImage('uploads/agency_media/thumbs', $request->file('thumbnail'));
        } elseif ($request->type === 'video' && $request->url) {
            $ytId = self::youtubeId($request->url);
            if ($ytId) {
                $thumbPath = "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg";
            }
        }

        $files     = $request->file('files') ?? [];
        $sortStart = (int) ($request->sort_order ?? 0);

        if (count($files) > 0) {
            foreach ($files as $i => $file) {
                $agency->media()->create(array_merge($base, [
                    'file_path'  => 'uploads/agency_media/' . uploadImage('uploads/agency_media', $file),
                    'thumbnail'  => $thumbPath,
                    'sort_order' => $sortStart + $i,
                ]));
            }
        } else {
            $agency->media()->create(array_merge($base, [
                'thumbnail'  => $thumbPath,
                'sort_order' => $sortStart,
            ]));
        }

        $count = count($files) ?: 1;
        return redirect()->route('admin.agencies.media.index', $agency)
            ->with('success', "تم إضافة {$count} وسيط بنجاح.");
    }

    public function edit(Agency $agency, AgencyMedia $medium)
    {
        $sectors = $this->sectors();
        return view('admin.agency_media.edit', compact('agency', 'medium', 'sectors'));
    }

    public function update(Request $request, Agency $agency, AgencyMedia $medium)
    {
        $request->validate([
            'type'       => 'required|in:video,image,visual_identity,website',
            'title_en'   => 'nullable|string|max:300',
            'title_ar'   => 'nullable|string|max:300',
            'url'        => 'nullable|string|max:500',
            'file'       => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm|max:20480',
            'thumbnail'  => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'sector'     => 'nullable|string|max:100',
        ]);

        $data = [
            'type'       => $request->type,
            'title_en'   => $request->title_en,
            'title_ar'   => $request->title_ar,
            'url'        => $request->url,
            'sort_order' => $request->sort_order ?? 0,
            'sector'     => $request->sector ?: null,
            'is_active'  => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = 'uploads/agency_media/' . uploadImage('uploads/agency_media', $request->file('file'));
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = 'uploads/agency_media/thumbs/' . uploadImage('uploads/agency_media/thumbs', $request->file('thumbnail'));
        } elseif ($request->type === 'video' && $request->url && !$medium->thumbnail) {
            $ytId = self::youtubeId($request->url);
            if ($ytId) {
                $data['thumbnail'] = "https://img.youtube.com/vi/{$ytId}/hqdefault.jpg";
            }
        }

        $medium->update($data);

        return redirect()->route('admin.agencies.media.index', $agency)->with('success', 'تم تحديث الوسيط بنجاح.');
    }

    public function patchSector(Request $request, Agency $agency, AgencyMedia $medium)
    {
        $request->validate(['sector' => 'nullable|string|max:100']);
        $medium->update(['sector' => $request->sector ?: null]);
        return back()->with('success', 'تم تحديث القطاع.');
    }

    private static function youtubeId(string $url): ?string
    {
        preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([^&\s?#]+)/', $url, $m);
        return $m[1] ?? null;
    }

    public function destroy(Agency $agency, AgencyMedia $medium)
    {
        $medium->delete();
        return back()->with('success', 'تم حذف الوسيط.');
    }
}
