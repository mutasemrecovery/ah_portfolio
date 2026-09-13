@extends('admin.layouts.app')
@section('title', 'تعديل خدمة')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">تعديل خدمة</h1>
        <p class="page-sub">{{ $agency->name_en }}</p>
    </div>
    <a href="{{ route('admin.agencies.services.index', $agency) }}" class="btn-outline-sm">
        <i class="bi bi-arrow-right"></i> العودة
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.agencies.services.update', [$agency, $service]) }}" method="POST">
@csrf @method('PUT')

<div class="panel-card">
    <div class="panel-card-body">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Service Title (EN) <span class="text-danger">*</span></label>
                <input type="text" name="title_en" value="{{ old('title_en', $service->title_en) }}"
                       class="form-control @error('title_en') is-invalid @enderror" required>
                @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">عنوان الخدمة (AR) <span class="text-danger">*</span></label>
                <input type="text" name="title_ar" value="{{ old('title_ar', $service->title_ar) }}"
                       class="form-control @error('title_ar') is-invalid @enderror" dir="rtl" required>
                @error('title_ar')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">الترتيب</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}" class="form-control" min="0">
            </div>
            <div class="col-md-3">
                <label class="form-label">نشط</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           {{ $service->is_active ? 'checked' : '' }}>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4 pb-4">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-save"></i> حفظ</button>
    <a href="{{ route('admin.agencies.services.index', $agency) }}" class="btn-outline-sm">إلغاء</a>
</div>

</form>
@endsection
