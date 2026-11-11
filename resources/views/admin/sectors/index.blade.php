@extends('admin.layouts.app')
@section('title', 'القطاعات')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">القطاعات</h1>
        <p class="page-sub">قطاعات الأعمال — تُستخدم لتصفية المحتوى في الموقع</p>
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

<div class="row g-4">
    {{-- Add form --}}
    <div class="col-md-4">
        <div class="panel-card h-100">
            <div class="panel-card-header">
                <h2 class="panel-card-title"><i class="bi bi-plus-circle"></i> إضافة قطاع جديد</h2>
            </div>
            <div class="panel-card-body">
                <form action="{{ route('admin.sectors.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">اسم القطاع <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="مثال: مطاعم، مطابخ، عيادات، مراكز لياقة...">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <small class="text-muted">سيظهر كزر فلتر في الموقع لما تضيفه على الأعمال</small>
                    </div>
                    <button type="submit" class="btn-primary-sm w-100">
                        <i class="bi bi-plus-lg"></i> إضافة
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Sectors list --}}
    <div class="col-md-8">
        <div class="panel-card">
            <div class="panel-card-header d-flex align-items-center justify-content-between">
                <h2 class="panel-card-title"><i class="bi bi-tags"></i> القطاعات المتاحة</h2>
                <span class="pill pill-info">{{ $sectors->count() }} قطاع</span>
            </div>
            <div class="panel-card-body p-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم القطاع</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sectors as $sector)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <span class="pill pill-neutral" style="font-size:13px">{{ $sector->name }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.sectors.destroy', $sector) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('حذف القطاع؟ لن يُحذف من الأعمال المرتبطة به.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-sm btn-delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-5">
                                <i class="bi bi-tags fs-4 d-block mb-2"></i>
                                لا توجد قطاعات بعد — أضف أول قطاع من النموذج
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
