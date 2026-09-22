@extends('admin.layouts.app')
@section('title', 'أعمال: ' . $agency->name_en)

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">أعمال: {{ $agency->name_en }}</h1>
        <p class="page-sub">إدارة الفيديوهات والصور والمواقع الخاصة بهذه الوكالة</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.agencies.media.create', $agency) }}" class="btn-primary-sm">
            <i class="bi bi-plus-lg"></i> إضافة وسيط
        </a>
        <a href="{{ route('admin.agencies.edit', $agency) }}" class="btn-outline-sm">
            <i class="bi bi-arrow-right"></i> العودة
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="panel-card">
    <div class="panel-card-header">
        <h2 class="panel-card-title"><i class="bi bi-images"></i> قائمة الأعمال</h2>
        <span class="pill pill-info">{{ $media->count() }} وسيط</span>
    </div>
    <div class="panel-card-body p-0">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>النوع</th>
                        <th>العنوان</th>
                        <th>الملف / URL</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($media as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @php
                                $labels = [
                                    'video' => 'Video',
                                    'image' => 'Image',
                                    'visual_identity' => 'Visual Identity',
                                    'website' => 'Website',
                                ];
                                $badges = [
                                    'video' => 'pill-info',
                                    'image' => 'pill-success',
                                    'visual_identity' => 'pill-warning',
                                    'website' => 'pill-neutral',
                                ];
                                $badge = $badges[$item->type] ?? 'pill-neutral';
                            @endphp
                            <span class="pill {{ $badge }}">{{ $labels[$item->type] ?? ucfirst($item->type) }}</span>
                        </td>
                        <td>{{ $item->title_en ?: '—' }}</td>
                        <td>
                            @if($item->file_path)
                                <a href="{{ asset($item->file_path) }}" target="_blank" class="text-primary small">ملف</a>
                            @elseif($item->url)
                                <a href="{{ $item->url }}" target="_blank" class="text-primary small">رابط</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $item->sort_order }}</td>
                        <td>
                            @if($item->is_active)
                                <span class="pill pill-success">نشط</span>
                            @else
                                <span class="pill pill-danger">معطّل</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.agencies.media.edit', [$agency, $item]) }}"
                                   class="btn-icon-sm btn-edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.agencies.media.destroy', [$agency, $item]) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('حذف هذا الوسيط؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-sm btn-delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            لا توجد أعمال
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
