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
                <label class="form-label">القطاع</label>
                <input type="text" name="sector" value="{{ old('sector') }}"
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
            <div class="col-12" id="url-wrap">
                <label class="form-label" id="url-label">URL</label>
                <input type="text" name="url" id="media-url" value="{{ old('url') }}" class="form-control"
                       placeholder="https://...">
                <small class="text-muted" id="url-hint"></small>
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

    const urlWrapEl  = document.getElementById('url-wrap');
    const urlLabelEl = document.getElementById('url-label');
    const urlHintEl  = document.getElementById('url-hint');
    const urlEl      = document.getElementById('media-url');

    // file section config
    const fileCfg = {
        video:           { show: false },
        image:           { accept: 'image/*,.jpg,.jpeg,.png,.gif', label: 'صور',       hint: 'JPG, PNG, GIF — Max 20MB لكل ملف', show: true },
        visual_identity: { accept: '.pdf,application/pdf',         label: 'ملفات PDF', hint: 'PDF فقط — Max 20MB لكل ملف',       show: true },
        website:         { show: false },
    };

    // url section config
    const urlCfg = {
        video:           { show: true,  label: 'رابط يوتيوب',     hint: 'الصق رابط الفيديو من يوتيوب — مثال: https://youtu.be/xxxxx', required: true  },
        image:           { show: false, label: 'URL', hint: '', required: false },
        visual_identity: { show: false, label: 'URL', hint: '', required: false },
        website:         { show: true,  label: 'رابط الموقع',      hint: 'https://example.com',                                          required: true  },
    };

    typeEl.addEventListener('change', function () {
        const fc = fileCfg[this.value] || fileCfg.image;
        if (fc.show) {
            fileEl.accept        = fc.accept;
            labelEl.textContent  = fc.label;
            hintEl.textContent   = fc.hint;
            wrapEl.style.display = '';
        } else {
            wrapEl.style.display = 'none';
        }

        const uc = urlCfg[this.value] || urlCfg.website;
        if (uc.show) {
            urlLabelEl.textContent  = uc.label;
            urlHintEl.textContent   = uc.hint;
            urlEl.required          = uc.required;
            urlWrapEl.style.display = '';
        } else {
            urlEl.required          = false;
            urlWrapEl.style.display = 'none';
        }
    });

    typeEl.dispatchEvent(new Event('change'));
})();
</script>
@endpush
@endsection
