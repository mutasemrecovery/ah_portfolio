@extends('admin.layouts.app')
@section('title', 'خدمات: ' . $agency->name_en)

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">خدمات: {{ $agency->name_en }}</h1>
        <p class="page-sub">إدارة قائمة الخدمات لهذه الوكالة</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.agencies.services.create', $agency) }}" class="btn-primary-sm">
            <i class="bi bi-plus-lg"></i> إضافة خدمة
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
    <div class="panel-card-header">
        <h2 class="panel-card-title"><i class="bi bi-list-check"></i> قائمة الخدمات</h2>
        <span class="pill pill-info">{{ $services->count() }} خدمة</span>
    </div>
    <div class="panel-card-body p-0">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الخدمة (EN)</th>
                        <th>الخدمة (AR)</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $service->title_en }}</td>
                        <td dir="rtl">{{ $service->title_ar }}</td>
                        <td>{{ $service->sort_order }}</td>
                        <td>
                            @if($service->is_active)
                                <span class="pill pill-success">نشط</span>
                            @else
                                <span class="pill pill-danger">معطّل</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.agencies.services.edit', [$agency, $service]) }}"
                                   class="btn-icon-sm btn-edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.agencies.services.destroy', [$agency, $service]) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('حذف هذه الخدمة؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-sm btn-delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            لا توجد خدمات
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
