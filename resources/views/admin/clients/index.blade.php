@extends('admin.layouts.app')
@section('title', 'العملاء')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">العملاء</h1>
        <p class="page-sub">إدارة شعارات العملاء في شريط التمرير</p>
    </div>
    <a href="{{ route('admin.clients.create') }}" class="btn-primary-sm">
        <i class="bi bi-plus-lg"></i> إضافة عميل
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="panel-card">
    <div class="panel-card-header">
        <h2 class="panel-card-title"><i class="bi bi-people"></i> قائمة العملاء</h2>
        <span class="pill pill-info">{{ $clients->count() }} عميل</span>
    </div>
    <div class="panel-card-body p-0">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الشعار</th>
                        <th>الاسم EN</th>
                        <th>الاسم AR</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($client->logo)
                                <img src="{{ asset($client->logo) }}" alt="" style="width:50px;height:40px;object-fit:contain;">
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>{{ $client->name_en }}</td>
                        <td dir="rtl">{{ $client->name_ar }}</td>
                        <td>{{ $client->sort_order }}</td>
                        <td>
                            @if($client->is_active)
                                <span class="pill pill-success">نشط</span>
                            @else
                                <span class="pill pill-danger">معطّل</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.clients.edit', $client) }}" class="btn-icon-sm btn-edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.clients.destroy', $client) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('حذف «{{ $client->name_en }}»؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon-sm btn-delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            لا يوجد عملاء
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
