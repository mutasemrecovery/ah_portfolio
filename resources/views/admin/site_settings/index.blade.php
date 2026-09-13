@extends('admin.layouts.app')
@section('title', __('messages.site_settings'))

@section('content')

<div class="page-header d-flex align-items-start justify-content-between flex-wrap gap-3">
    <div>
        <h1 class="page-title">{{ __('messages.site_settings') }}</h1>
        <p class="page-sub">{{ __('messages.site_settings_sub') }}</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── Text / URL Settings ──────────────────────────────────────── --}}
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

<div class="panel-card mb-4">
    <div class="panel-card-header">
        <h2 class="panel-card-title"><i class="bi bi-sliders"></i> General Settings / الإعدادات العامة</h2>
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

<div class="d-flex gap-2 mb-5">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-save"></i> Save Settings / حفظ الإعدادات</button>
</div>

</form>

{{-- ── Decorative Images ────────────────────────────────────────── --}}
<form action="{{ route('admin.site-settings.upload-images') }}" method="POST" enctype="multipart/form-data">
@csrf

@php
$imageFields = [
    'hero_sign_image'    => 'Hero — Sign Board (sign__img)',
    'hero_octo_image'    => 'Hero — Octopus Mascot (octo--hero)',
    'agencies_octo_image'=> 'Agencies — Octopus Peeking (octo--peek)',
    'about_octo_image'   => 'About — Octopus Sitting (octo--about)',
    'showcase_octo_image'=> 'Showcase — Octopus (octo--recovery)',
    'cta_octo_image'     => 'CTA — Octopus with Sign (octo--cta)',
];
@endphp

<div class="panel-card mb-4">
    <div class="panel-card-header">
        <h2 class="panel-card-title"><i class="bi bi-images"></i> Decorative Images / الصور الزخرفية</h2>
        <small class="text-muted">Upload images to replace placeholder graphics on the homepage</small>
    </div>
    <div class="panel-card-body">
        <div class="row g-4">
            @foreach($imageFields as $key => $label)
            @php $currentPath = $settings[$key]->value_en ?? null; @endphp
            <div class="col-md-4">
                <label class="form-label fw-semibold">{{ $label }}</label>

                @if($currentPath)
                <div class="mb-2">
                    <img src="{{ asset($currentPath) }}"
                         alt="{{ $label }}"
                         class="img-thumbnail d-block"
                         style="max-height:140px;object-fit:contain;background:#f8f9fa;">
                </div>
                @else
                <div class="mb-2 d-flex align-items-center justify-content-center rounded border"
                     style="height:100px;background:#f8f9fa;color:#94a3b8;font-size:.78rem;">
                    No image uploaded
                </div>
                @endif

                <input type="file"
                       name="images[{{ $key }}]"
                       accept="image/jpeg,image/png,image/webp,image/gif"
                       class="form-control form-control-sm">
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="d-flex gap-2 pb-4">
    <button type="submit" class="btn-primary-sm"><i class="bi bi-cloud-upload"></i> Upload Images / رفع الصور</button>
</div>

</form>

@endsection
