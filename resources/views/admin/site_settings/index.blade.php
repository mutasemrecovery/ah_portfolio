@extends('admin.layouts.app')
@section('title', 'إعدادات الموقع')

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">إعدادات الموقع</h1>
        <p class="page-sub">إدارة معلومات التواصل والروابط الاجتماعية وبيانات الموقع العامة</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form action="{{ route('admin.site-settings.update') }}" method="POST">
@csrf @method('PUT')

@php
$fields = [
    'brand_name'     => ['label' => 'Brand Name / اسم الموقع',         'en' => true, 'ar' => true],
    'email'          => ['label' => 'Email / البريد الإلكتروني',        'en' => true, 'ar' => false],
    'phone'          => ['label' => 'Phone / رقم الهاتف',              'en' => true, 'ar' => false],
    'facebook_url'   => ['label' => 'Facebook URL',                     'en' => true, 'ar' => false],
    'instagram_url'  => ['label' => 'Instagram URL',                    'en' => true, 'ar' => false],
    'website_url'    => ['label' => 'Website URL',                      'en' => true, 'ar' => false],
    'agencies_title' => ['label' => 'Agencies Section Title / عنوان قسم الوكالات', 'en' => true, 'ar' => true],
    'clients_title'  => ['label' => 'Clients Section Title / عنوان قسم العملاء',  'en' => true, 'ar' => true],
    'clients_sub'    => ['label' => 'Clients Subtitle / وصف قسم العملاء',          'en' => true, 'ar' => true],
    'cta_lead'       => ['label' => 'CTA Lead Text / نص CTA الرئيسي',              'en' => true, 'ar' => true],
    'cta_btn_text'   => ['label' => 'CTA Button Text / نص زر CTA',                 'en' => true, 'ar' => true],
    'cta_btn_link'   => ['label' => 'CTA Button Link / رابط زر CTA',               'en' => true, 'ar' => false],
];
@endphp

<div class="panel-card">
    <div class="panel-card-header">
        <h2 class="panel-card-title"><i class="bi bi-sliders"></i> الإعدادات العامة</h2>
    </div>
    <div class="panel-card-body">
        <div class="row g-4">
            @foreach($fields as $key => $meta)
            <div class="col-12">
                <label class="form-label fw-semibold">{{ $meta['label'] }}</label>
                <div class="row g-2">
                    @if($meta['en'])
                    <div class="{{ $meta['ar'] ? 'col-md-6' : 'col-12' }}">
                        <input type="text" name="settings[{{ $key }}][value_en]"
                               value="{{ old("settings.{$key}.value_en", $settings[$key]->value_en ?? '') }}"
                               class="form-control" placeholder="English">
                    </div>
                    @endif
                    @if($meta['ar'])
                    <div class="{{ $meta['en'] ? 'col-md-6' : 'col-12' }}">
                        <input type="text" name="settings[{{ $key }}][value_ar]"
                               value="{{ old("settings.{$key}.value_ar", $settings[$key]->value_ar ?? '') }}"
                               class="form-control" placeholder="العربية" dir="rtl">
                    </div>
                    @else
                    <input type="hidden" name="settings[{{ $key }}][value_ar]"
                           value="{{ $settings[$key]->value_ar ?? '' }}">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4 pb-4">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-save"></i> حفظ الإعدادات</button>
</div>

</form>

@endsection
