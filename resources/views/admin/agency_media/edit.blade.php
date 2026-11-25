@extends('admin.layouts.app')
@section('title', 'تعديل وسيط')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">تعديل وسيط</h1>
        <p class="page-sub">{{ $agency->name_en }}</p>
    </div>
    <a href="{{ route('admin.agencies.media.index', $agency) }}" class="btn-outline-sm">
        <i class="bi bi-arrow-right"></i> العودة
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.agencies.media.update', [$agency, $medium]) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="panel-card">
    <div class="panel-card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">النوع <span class="text-danger">*</span></label>
                <select name="type" class="form-control" required>
                    <option value="video"   {{ old('type', $medium->type) == 'video'   ? 'selected' : '' }}>Video</option>
                    <option value="image"   {{ old('type', $medium->type) == 'image'   ? 'selected' : '' }}>Image</option>
                    <option value="visual_identity" {{ old('type', $medium->type) == 'visual_identity' ? 'selected' : '' }}>Visual Identity</option>
                    <option value="website" {{ old('type', $medium->type) == 'website' ? 'selected' : '' }}>Website</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">القطاع</label>
                <input type="text" name="sector" value="{{ old('sector', $medium->sector) }}"
                       class="form-control" list="sectors-datalist"
                       placeholder="مثال: مطاعم، مطابخ، عيادات...">
                <datalist id="sectors-datalist">
                    @foreach($sectors as $s)
                        <option value="{{ $s }}">
                    @endforeach
                </datalist>
                <small class="text-muted">اختر من القائمة أو اكتب قطاعاً جديداً</small>
            </div>
            <div class="col-md-4">
                <label class="form-label">الترتيب</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $medium->sort_order) }}" class="form-control" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">نشط</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           {{ $medium->is_active ? 'checked' : '' }}>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">العنوان (EN)</label>
                <input type="text" name="title_en" value="{{ old('title_en', $medium->title_en) }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">العنوان (AR)</label>
                <input type="text" name="title_ar" value="{{ old('title_ar', $medium->title_ar) }}" class="form-control" dir="rtl">
            </div>
            <div class="col-12">
                <label class="form-label">ملف جديد (صورة / فيديو / PDF)</label>
                @if($medium->file_path)
                @php
                    $ext = strtolower(pathinfo($medium->file_path, PATHINFO_EXTENSION));
                    $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                    $isPdf = $ext === 'pdf';
                @endphp
                <div class="mb-2">
                    @if($isImg)
                        <img src="{{ asset($medium->file_path) }}" alt=""
                             class="img-thumbnail d-block"
                             style="max-height:160px;max-width:280px;object-fit:contain;background:#f8f9fa;">
                    @elseif($isPdf)
                        <div class="d-flex align-items-center gap-2 p-2 rounded border bg-light" style="max-width:320px;">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#e53935" stroke-width="1.6">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <div>
                                <div class="small fw-semibold">{{ basename($medium->file_path) }}</div>
                                <a href="{{ asset($medium->file_path) }}" target="_blank" class="small text-primary">فتح الملف</a>
                            </div>
                        </div>
                    @else
                        <div class="small text-muted">الملف الحالي: <a href="{{ asset($medium->file_path) }}" target="_blank">{{ basename($medium->file_path) }}</a></div>
                    @endif
                </div>
                @endif
                <input type="file" name="file" class="form-control" accept="image/*,video/*,application/pdf,.pdf">
                <small class="text-muted">اترك فارغاً للإبقاء على الملف الحالي</small>
            </div>
            <div class="col-12">
                <label class="form-label">Thumbnail جديد</label>
                @if($medium->thumbnail)
                <div class="mb-2">
                    <img src="{{ asset($medium->thumbnail) }}" alt="" class="img-thumbnail" style="max-height:60px;">
                </div>
                @endif
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                <small class="text-muted">اترك فارغاً للإبقاء على الحالي</small>
            </div>
            <div class="col-12">
                <label class="form-label">URL</label>
                <input type="text" name="url" value="{{ old('url', $medium->url) }}" class="form-control">
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4 pb-4">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-save"></i> حفظ</button>
    <a href="{{ route('admin.agencies.media.index', $agency) }}" class="btn-outline-sm">إلغاء</a>
</div>

</form>
@endsection
