@extends('admin.layouts.app')
@section('title', 'تعديل وكالة')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">تعديل: {{ $agency->name_en }}</h1>
        <p class="page-sub">تعديل بيانات الوكالة وإدارة خدماتها وأعمالها</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.agencies.services.index', $agency) }}" class="btn-outline-sm">
            <i class="bi bi-list-check"></i> الخدمات ({{ $agency->services->count() }})
        </a>
        <a href="{{ route('admin.agencies.media.index', $agency) }}" class="btn-outline-sm">
            <i class="bi bi-images"></i> الأعمال ({{ $agency->media->count() }})
        </a>
        <a href="{{ route('admin.agencies.index') }}" class="btn-outline-sm">
            <i class="bi bi-arrow-right"></i> العودة
        </a>
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

<form action="{{ route('admin.agencies.update', $agency) }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')

<div class="row g-4">
    {{-- English --}}
    <div class="col-12 col-xl-6">
        <div class="panel-card h-100">
            <div class="panel-card-header"><h2 class="panel-card-title"><i class="bi bi-translate"></i> English</h2></div>
            <div class="panel-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Name (EN) <span class="text-danger">*</span></label>
                        <input type="text" name="name_en" value="{{ old('name_en', $agency->name_en) }}"
                               class="form-control @error('name_en') is-invalid @enderror" required>
                        @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description (EN)</label>
                        <textarea name="description_en" rows="3"
                                  class="form-control">{{ old('description_en', $agency->description_en) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Section Heading (EN)</label>
                        <input type="text" name="heading_en" value="{{ old('heading_en', $agency->heading_en) }}"
                               class="form-control">
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
                        <input type="text" name="name_ar" value="{{ old('name_ar', $agency->name_ar) }}"
                               class="form-control @error('name_ar') is-invalid @enderror" dir="rtl" required>
                        @error('name_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">الوصف (AR)</label>
                        <textarea name="description_ar" rows="3" dir="rtl"
                                  class="form-control">{{ old('description_ar', $agency->description_ar) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">عنوان القسم (AR)</label>
                        <input type="text" name="heading_ar" value="{{ old('heading_ar', $agency->heading_ar) }}"
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
                <div class="row g-3 align-items-start">
                    <div class="col-md-6">
                        <label class="form-label">شعار الوكالة (Logo)</label>
                        @if($agency->logo)
                        <div class="mb-2">
                            <img src="{{ asset($agency->logo) }}" alt="" class="img-thumbnail" style="max-height:80px;">
                        </div>
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">اترك فارغاً للإبقاء على الشعار الحالي</small>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $agency->sort_order) }}" class="form-control" min="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الحالة</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   {{ $agency->is_active ? 'checked' : '' }}>
                            <label class="form-check-label">نشط</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4 pb-4">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-save"></i> حفظ التغييرات</button>
    <a href="{{ route('admin.agencies.index') }}" class="btn-outline-sm">إلغاء</a>
</div>

</form>

@endsection
