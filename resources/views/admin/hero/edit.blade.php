@extends('admin.layouts.app')
@section('title', 'قسم Hero')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">قسم Hero</h1>
        <p class="page-sub">تعديل محتوى القسم الرئيسي في الصفحة الأمامية</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.hero.update') }}" method="POST">
@csrf @method('PUT')

<div class="row g-4">
    {{-- English --}}
    <div class="col-12 col-xl-6">
        <div class="panel-card h-100">
            <div class="panel-card-header">
                <h2 class="panel-card-title"><i class="bi bi-translate"></i> English</h2>
            </div>
            <div class="panel-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Title (EN) <span class="text-danger">*</span></label>
                        <input type="text" name="title_en" value="{{ old('title_en', $hero->title_en) }}"
                               class="form-control @error('title_en') is-invalid @enderror" required>
                        @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Subtitle (EN)</label>
                        <input type="text" name="subtitle_en" value="{{ old('subtitle_en', $hero->subtitle_en) }}"
                               class="form-control @error('subtitle_en') is-invalid @enderror">
                        @error('subtitle_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">CTA Button Text (EN)</label>
                        <input type="text" name="cta_text_en" value="{{ old('cta_text_en', $hero->cta_text_en) }}"
                               class="form-control @error('cta_text_en') is-invalid @enderror">
                        @error('cta_text_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Arabic --}}
    <div class="col-12 col-xl-6">
        <div class="panel-card h-100">
            <div class="panel-card-header">
                <h2 class="panel-card-title"><i class="bi bi-translate"></i> العربية</h2>
            </div>
            <div class="panel-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">العنوان (AR) <span class="text-danger">*</span></label>
                        <input type="text" name="title_ar" value="{{ old('title_ar', $hero->title_ar) }}"
                               class="form-control @error('title_ar') is-invalid @enderror" dir="rtl" required>
                        @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">العنوان الفرعي (AR)</label>
                        <input type="text" name="subtitle_ar" value="{{ old('subtitle_ar', $hero->subtitle_ar) }}"
                               class="form-control @error('subtitle_ar') is-invalid @enderror" dir="rtl">
                        @error('subtitle_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">نص زر CTA (AR)</label>
                        <input type="text" name="cta_text_ar" value="{{ old('cta_text_ar', $hero->cta_text_ar) }}"
                               class="form-control @error('cta_text_ar') is-invalid @enderror" dir="rtl">
                        @error('cta_text_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CTA Link --}}
    <div class="col-12">
        <div class="panel-card">
            <div class="panel-card-header">
                <h2 class="panel-card-title"><i class="bi bi-link-45deg"></i> رابط الزر</h2>
            </div>
            <div class="panel-card-body">
                <label class="form-label">CTA Link</label>
                <input type="text" name="cta_link" value="{{ old('cta_link', $hero->cta_link) }}"
                       class="form-control" placeholder="مثال: #agencies أو /contact">
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4 pb-4">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-save"></i> حفظ التغييرات</button>
</div>

</form>

@endsection
