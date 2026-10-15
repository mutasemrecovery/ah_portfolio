@extends('admin.layouts.app')
@section('title', 'إضافة وسيط')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">إضافة وسيط لـ: {{ $agency->name_en }}</h1>
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

<form action="{{ route('admin.agencies.media.store', $agency) }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="panel-card">
    <div class="panel-card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">النوع <span class="text-danger">*</span></label>
                <select name="type" id="media-type" class="form-control @error('type') is-invalid @enderror" required>
                    <option value="video"            {{ old('type') == 'video'            ? 'selected' : '' }}>Video</option>
                    <option value="image"            {{ old('type') == 'image'            ? 'selected' : '' }}>Image</option>
                    <option value="visual_identity"  {{ old('type') == 'visual_identity'  ? 'selected' : '' }}>Visual Identity</option>
                    <option value="website"          {{ old('type') == 'website'          ? 'selected' : '' }}>Website</option>
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">الترتيب</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control" min="0">
            </div>
            <div class="col-md-4">
                <label class="form-label">نشط</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">العنوان (EN)</label>
                <input type="text" name="title_en" value="{{ old('title_en') }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">العنوان (AR)</label>
                <input type="text" name="title_ar" value="{{ old('title_ar') }}" class="form-control" dir="rtl">
            </div>
            <div class="col-12" id="files-wrap">
                <label class="form-label" id="files-label">ملفات</label>
                <input type="file" name="files[]" id="media-files"
                       class="form-control @error('files.*') is-invalid @enderror"
                       accept="image/*,video/*" multiple>
                <small class="text-muted" id="files-hint">يمكنك اختيار أكثر من ملف دفعة واحدة — JPG, PNG, GIF, MP4, WEBM — Max 20MB لكل ملف</small>
                @error('files.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Thumbnail (للفيديوهات والمواقع)</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                <small class="text-muted">PNG, JPG — Max 2MB</small>
            </div>
            <div class="col-12">
                <label class="form-label">URL (للمواقع أو روابط YouTube)</label>
                <input type="text" name="url" value="{{ old('url') }}" class="form-control"
                       placeholder="https://...">
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4 pb-4">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-save"></i> حفظ</button>
    <a href="{{ route('admin.agencies.media.index', $agency) }}" class="btn-outline-sm">إلغاء</a>
</div>

</form>

@push('scripts')
<script>
(function () {
    const typeEl  = document.getElementById('media-type');
    const fileEl  = document.getElementById('media-files');
    const labelEl = document.getElementById('files-label');
    const hintEl  = document.getElementById('files-hint');
    const wrapEl  = document.getElementById('files-wrap');

    const cfg = {
        video:           { accept: 'video/*,.mp4,.webm',          label: 'فيديوهات',           hint: 'MP4, WEBM — Max 20MB لكل ملف', show: true  },
        image:           { accept: 'image/*,.jpg,.jpeg,.png,.gif', label: 'صور',                hint: 'JPG, PNG, GIF — Max 20MB لكل ملف', show: true  },
        visual_identity: { accept: '.pdf,application/pdf',         label: 'ملفات PDF',          hint: 'PDF فقط — Max 20MB لكل ملف', show: true  },
        website:         { accept: '',                             label: 'ملفات',              hint: '',                            show: false },
    };

    typeEl.addEventListener('change', function () {
        const c = cfg[this.value] || cfg.image;
        fileEl.accept    = c.accept;
        labelEl.textContent = c.label;
        hintEl.textContent  = c.hint;
        wrapEl.style.display = c.show ? '' : 'none';
    });

    typeEl.dispatchEvent(new Event('change'));
})();
</script>
@endpush
@endsection
