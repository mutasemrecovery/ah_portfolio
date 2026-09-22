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
                <label class="form-label">ملف جديد (صورة / فيديو)</label>
                @if($medium->file_path)
                <div class="mb-2 small text-muted">الملف الحالي: <a href="{{ asset($medium->file_path) }}" target="_blank">عرض</a></div>
                @endif
                <input type="file" name="file" class="form-control" accept="image/*,video/*">
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
