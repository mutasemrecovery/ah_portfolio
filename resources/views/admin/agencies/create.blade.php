@extends('admin.layouts.app')
@section('title', 'إضافة وكالة')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">إضافة وكالة جديدة</h1>
        <p class="page-sub">أنشئ وكالة جديدة في المنظومة</p>
    </div>
    <a href="{{ route('admin.agencies.index') }}" class="btn-outline-sm">
        <i class="bi bi-arrow-right"></i> العودة
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.agencies.store') }}" method="POST" enctype="multipart/form-data">
@csrf

<div class="row g-4">
    {{-- English --}}
    <div class="col-12 col-xl-6">
        <div class="panel-card h-100">
            <div class="panel-card-header"><h2 class="panel-card-title"><i class="bi bi-translate"></i> English</h2></div>
            <div class="panel-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Name (EN) <span class="text-danger">*</span></label>
                        <input type="text" name="name_en" value="{{ old('name_en') }}"
                               class="form-control @error('name_en') is-invalid @enderror" required>
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description (EN)</label>
                        <textarea name="description_en" rows="3"
                                  class="form-control @error('description_en') is-invalid @enderror">{{ old('description_en') }}</textarea>
                        @error('description_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Section Heading (EN)</label>
                        <input type="text" name="heading_en" value="{{ old('heading_en') }}"
                               class="form-control" placeholder="Recovery Jo – Digital & Tech Marketing">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Arabic --}}
    <div class="col-12 col-xl-6">
        <div class="panel-card h-100">
            <div class="panel-card-header"><h2 class="panel-card-title"><i class="bi bi-translate"></i> العربية</h2></div>
            <div class="panel-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">الاسم (AR) <span class="text-danger">*</span></label>
                        <input type="text" name="name_ar" value="{{ old('name_ar') }}"
                               class="form-control @error('name_ar') is-invalid @enderror" dir="rtl" required>
                        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">الوصف (AR)</label>
                        <textarea name="description_ar" rows="3" dir="rtl"
                                  class="form-control @error('description_ar') is-invalid @enderror">{{ old('description_ar') }}</textarea>
                        @error('description_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">عنوان القسم (AR)</label>
                        <input type="text" name="heading_ar" value="{{ old('heading_ar') }}"
                               class="form-control" dir="rtl">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Settings --}}
    <div class="col-12">
        <div class="panel-card">
            <div class="panel-card-header"><h2 class="panel-card-title"><i class="bi bi-gear"></i> الإعدادات</h2></div>
            <div class="panel-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">شعار الوكالة (Logo)</label>
                        <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">PNG, JPG — Max 2MB</small>
                        @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الحالة</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">نشط</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4 pb-4">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-save"></i> حفظ الوكالة</button>
    <a href="{{ route('admin.agencies.index') }}" class="btn-outline-sm">إلغاء</a>
</div>

</form>

@endsection
