@extends('admin.layouts.app')
@section('title', 'قسم About')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">قسم About</h1>
        <p class="page-sub">تعديل محتوى قسم "من نحن"</p>
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

<form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data">
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
                        <input type="text" name="title_en" value="{{ old('title_en', $about->title_en) }}"
                               class="form-control @error('title_en') is-invalid @enderror" required>
                        @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Body (EN) <span class="text-danger">*</span></label>
                        <textarea name="body_en" rows="8"
                                  class="form-control @error('body_en') is-invalid @enderror"
                                  required>{{ old('body_en', $about->body_en) }}</textarea>
                        <small class="text-muted">يمكن استخدام سطر جديد لفصل الفقرات</small>
                        @error('body_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                        <input type="text" name="title_ar" value="{{ old('title_ar', $about->title_ar) }}"
                               class="form-control @error('title_ar') is-invalid @enderror" dir="rtl" required>
                        @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">المحتوى (AR) <span class="text-danger">*</span></label>
                        <textarea name="body_ar" rows="8" dir="rtl"
                                  class="form-control @error('body_ar') is-invalid @enderror"
                                  required>{{ old('body_ar', $about->body_ar) }}</textarea>
                        <small class="text-muted">يمكن استخدام سطر جديد لفصل الفقرات</small>
                        @error('body_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Image --}}
    <div class="col-12">
        <div class="panel-card">
            <div class="panel-card-header">
                <h2 class="panel-card-title"><i class="bi bi-image"></i> صورة القسم</h2>
            </div>
            <div class="panel-card-body">
                @if($about->image)
                <div class="mb-3">
                    <img src="{{ asset($about->image) }}" alt="About Image" class="img-thumbnail" style="max-height:150px;">
                </div>
                @endif
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                <small class="text-muted">PNG, JPG — Max 3MB</small>
                @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4 pb-4">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-save"></i> حفظ التغييرات</button>
</div>

</form>

@endsection
