@extends('admin.layouts.app')

@section('title', __('messages.page_dashboard'))

@section('content')

{{-- Page Header --}}
<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">{{ __('messages.page_dashboard') }}</h1>
        <p class="page-sub">{{ __('messages.welcome_back') }}</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">{{ __('messages.page_dashboard') }}</li>
        </ol>
    </nav>
</div>

{{-- Flash Message --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif


@endsection
