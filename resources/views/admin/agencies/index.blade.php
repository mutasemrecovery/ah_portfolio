@extends('admin.layouts.app')
@section('title', 'الوكالات')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">الوكالات</h1>
        <p class="page-sub">إدارة الوكالات المتكاملة وخدماتها وأعمالها</p>
    </div>
    <a href="{{ route('admin.agencies.create') }}" class="btn-primary-sm">
        <i class="bi bi-plus-lg"></i> إضافة وكالة
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="panel-card">
    <div class="panel-card-header">
        <h2 class="panel-card-title"><i class="bi bi-buildings"></i> قائمة الوكالات</h2>
        <span class="pill pill-info">{{ $agencies->count() }} وكالة</span>
    </div>
    <div class="panel-card-body p-0">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الخدمات</th>
                        <th>الأعمال</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agencies as $agency)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($agency->logo)
                                <img src="{{ asset($agency->logo) }}" alt="" style="width:40px;height:40px;object-fit:contain;border-radius:6px;">
                                @else
                                <div style="width:40px;height:40px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-building text-muted"></i>
                                </div>
                                @endif
                                <div>
                                    <div class="fw-semibold">{{ $agency->name_en }}</div>
                                    <div class="text-muted small" dir="rtl">{{ $agency->name_ar }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.agencies.services.index', $agency) }}" class="pill pill-info">
                                {{ $agency->services_count }} خدمة
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.agencies.media.index', $agency) }}" class="pill pill-neutral">
                                {{ $agency->media_count }} وسيط
                            </a>
                        </td>
                        <td>
                            @if($agency->is_active)
                                <span class="pill pill-success">نشط</span>
                            @else
                                <span class="pill pill-danger">معطّل</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.agencies.edit', $agency) }}" class="btn-icon-sm btn-edit" title="تعديل">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.agencies.destroy', $agency) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف وكالة «{{ $agency->name_en }}»؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-sm btn-delete" title="حذف">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            لا توجد وكالات
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
