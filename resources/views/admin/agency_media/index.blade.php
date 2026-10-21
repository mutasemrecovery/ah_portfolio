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
    <div class="panel-card-header d-flex align-items-center justify-content-between flex-wrap gap-3">
        <h2 class="panel-card-title"><i class="bi bi-images"></i> قائمة الأعمال</h2>
        <span class="pill pill-info" id="visible-count">{{ $media->count() }} وسيط</span>
    </div>

    {{-- Filter tabs --}}
    <div class="px-4 pt-3 pb-0 d-flex gap-2 flex-wrap" id="type-filters">
        <button class="filter-btn active" data-filter="all">الكل <span class="filter-count">{{ $media->count() }}</span></button>
        @foreach(['video' => 'Video', 'image' => 'Image', 'visual_identity' => 'Visual Identity', 'website' => 'Website'] as $type => $label)
            @php $cnt = $media->where('type', $type)->count(); @endphp
            @if($cnt > 0)
            <button class="filter-btn" data-filter="{{ $type }}">{{ $label }} <span class="filter-count">{{ $cnt }}</span></button>
            @endif
        @endforeach
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
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="media-tbody">
                    @forelse($media as $item)
                    <tr data-type="{{ $item->type }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @php
                                $labels = ['video'=>'Video','image'=>'Image','visual_identity'=>'Visual Identity','website'=>'Website'];
                                $badges = ['video'=>'pill-info','image'=>'pill-success','visual_identity'=>'pill-warning','website'=>'pill-neutral'];
                            @endphp
                            <span class="pill {{ $badges[$item->type] ?? 'pill-neutral' }}">{{ $labels[$item->type] ?? ucfirst($item->type) }}</span>
                        </td>
                        <td>{{ $item->title_en ?: ($item->title_ar ?: '—') }}</td>
                        <td>
                            @if($item->url)
                                <a href="{{ $item->url }}" target="_blank" class="text-primary small">
                                    {{ str_starts_with($item->url, 'http') ? parse_url($item->url, PHP_URL_HOST) : $item->url }}
                                </a>
                            @elseif($item->file_path)
                                <a href="{{ asset($item->file_path) }}" target="_blank" class="text-primary small">ملف</a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
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
                    <tr id="empty-row">
                        <td colspan="6" class="text-center text-muted py-4">
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

@push('styles')
<style>
.filter-btn {
    padding: 6px 16px;
    border-radius: 20px;
    border: 1.5px solid #dee2e6;
    background: transparent;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    color: #6c757d;
    transition: all .2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.filter-btn:hover { border-color: #6c5ce7; color: #6c5ce7; }
.filter-btn.active { background: #6c5ce7; border-color: #6c5ce7; color: #fff; }
.filter-count {
    background: rgba(255,255,255,.25);
    border-radius: 10px;
    padding: 0 7px;
    font-size: 11px;
}
.filter-btn:not(.active) .filter-count { background: #f0f0f0; color: #555; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const btns    = document.querySelectorAll('.filter-btn');
    const rows    = [...document.querySelectorAll('#media-tbody tr[data-type]')];
    const counter = document.getElementById('visible-count');

    btns.forEach(btn => btn.addEventListener('click', function () {
        btns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        let visible  = 0;

        rows.forEach(row => {
            const show = filter === 'all' || row.dataset.type === filter;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        counter.textContent = visible + ' وسيط';
    }));
})();
</script>
@endpush

@endsection
