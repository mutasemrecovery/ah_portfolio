@extends('layouts.front')
@section('title', 'AH Group')

@section('content')

@php
    $locale = app()->getLocale();
    $setting = fn(string $key) => optional($settings[$key] ?? null)->{'value_' . $locale} ?? optional($settings[$key] ?? null)->value_en ?? '';
@endphp

<!-- ================= HERO ================= -->
<section class="hero" id="top">
  <div class="hero__pattern" aria-hidden="true"></div>
  <div class="hero__stage">
    <div class="rope rope--l" aria-hidden="true"><i></i></div>
    <div class="rope rope--r" aria-hidden="true"><i></i></div>
    <div class="sign" id="sign">
      <div class="sign__hole sign__hole--l"></div>
      <div class="sign__hole sign__hole--r"></div>
      @if($setting('hero_sign_image'))
        <img src="{{ asset($setting('hero_sign_image')) }}" alt="AH.GROUP sign board" class="sign__img">
      @else
        <span class="ph ph--sign" data-label="IMAGE — Sign board with AH.GROUP logo"></span>
      @endif
    </div>
    <div class="octo octo--hero float">
      @if($setting('hero_octo_image'))
        <img src="{{ asset($setting('hero_octo_image')) }}" alt="AH.GROUP mascot" class="octo__img">
      @else
        <span class="ph ph--octo" data-label="IMAGE — Octopus mascot (orange hoodie)"></span>
      @endif
    </div>
  </div>

  <div class="hero__copy reveal">
    <h1 class="display">{{ $hero?->{'title_' . $locale} ?? $hero?->title_en ?? __('front.hero_title') }}</h1>
    <p class="hero__sub">{{ $hero?->{'subtitle_' . $locale} ?? $hero?->subtitle_en ?? __('front.hero_subtitle') }}</p>
    <a href="{{ $hero?->cta_link ?? '#agencies' }}" class="btn btn--orange btn--lg">
      {{ $hero?->{'cta_text_' . $locale} ?? $hero?->cta_text_en ?? __('front.hero_cta') }}
    </a>
  </div>
</section>

<!-- ================= INTEGRATED AGENCIES ================= -->
<section class="agencies" id="agencies">
  <h2 class="rule-title reveal">
    <span>{{ $setting('agencies_title') ?: __('front.agencies_title') }}</span>
  </h2>

  <div class="agencies__grid">
    @forelse($agencies as $i => $agency)
    <article class="agcard reveal" @if($i > 0) style="--d:{{ ($i * 0.12) }}s" @endif>
      <div class="agcard__logo">
        @if($agency->logo)
          <img src="{{ asset($agency->logo) }}" alt="{{ $agency->{'name_' . $locale} }}">
        @else
          <span class="ph ph--wide" data-label="IMAGE — {{ $agency->name_en }} logo"></span>
        @endif
      </div>
      <p class="agcard__desc">{{ $agency->{'description_' . $locale} ?? $agency->description_en }}</p>
    </article>
    @empty
    <article class="agcard reveal">
      <div class="agcard__logo">
        <span class="ph ph--wide" data-label="IMAGE — Agency logo"></span>
      </div>
      <p class="agcard__desc">{{ __('front.agency_default_desc') }}</p>
    </article>
    @endforelse
  </div>

  <div class="octo octo--peek float">
    @if($setting('agencies_octo_image'))
      <img src="{{ asset($setting('agencies_octo_image')) }}" alt="" class="octo__img" aria-hidden="true">
    @else
      <span class="ph ph--octo" data-label="IMAGE — Octopus peeking (front)"></span>
    @endif
  </div>
</section>

<!-- ================= ABOUT ================= -->
<section class="about" id="about">
  <div class="about__inner">
    <div class="about__media reveal">
      <span class="about__frame" aria-hidden="true"></span>
      @if($about?->image)
        <img src="{{ asset($about->image) }}" alt="{{ $about->{'title_' . $locale} ?? $about->title_en }}">
      @else
        <span class="ph ph--about" data-label="IMAGE — Team at desk (about photo)"></span>
      @endif
    </div>

    <div class="about__text reveal">
      <h2 class="about__title">{{ $about?->{'title_' . $locale} ?? $about?->title_en ?? __('front.about_title') }}</h2>
      @if($about)
        @foreach(explode("\n", trim($about->{'body_' . $locale} ?? $about->body_en)) as $para)
          @if(trim($para))
            <p>{{ trim($para) }}</p>
          @endif
        @endforeach
      @else
        <p>{{ __('front.about_p1') }}</p>
        <p>{{ __('front.about_p2') }}</p>
        <p>{{ __('front.about_p3') }}</p>
      @endif
    </div>
  </div>

  <div class="octo octo--about float">
    @if($setting('about_octo_image'))
      <img src="{{ asset($setting('about_octo_image')) }}" alt="" class="octo__img" aria-hidden="true">
    @else
      <span class="ph ph--octo" data-label="IMAGE — Octopus sitting"></span>
    @endif
  </div>
</section>

<!-- ================= CLIENTS ================= -->
<section class="clients" id="clients">
  <h2 class="uline-title reveal">
    <span>{{ $setting('clients_title') ?: __('front.clients_title') }}</span>
  </h2>
  <p class="clients__sub reveal">
    {{ $setting('clients_sub') ?: __('front.clients_sub') }}
  </p>

  @php
    $clientChunks = $clients->isNotEmpty() ? $clients->chunk((int) ceil($clients->count() / 2)) : collect();
    $row1 = $clientChunks->get(0) ?? collect();
    $row2 = $clientChunks->get(1) ?? collect();
  @endphp

  @if($clients->isNotEmpty())
  <div class="marquee">
    <div class="marquee__track marquee__track--l">
      @foreach($row1 as $client)
        @if($client->logo)
          <img src="{{ asset($client->logo) }}" alt="{{ $client->{'name_' . $locale} }}" class="client-logo">
        @else
          <span class="ph ph--client" data-label="{{ $client->{'name_' . $locale} }}"></span>
        @endif
      @endforeach
    </div>
  </div>
  <div class="marquee">
    <div class="marquee__track marquee__track--r">
      @foreach($row2 as $client)
        @if($client->logo)
          <img src="{{ asset($client->logo) }}" alt="{{ $client->{'name_' . $locale} }}" class="client-logo">
        @else
          <span class="ph ph--client" data-label="{{ $client->{'name_' . $locale} }}"></span>
        @endif
      @endforeach
    </div>
  </div>
  @else
  <div class="marquee">
    <div class="marquee__track marquee__track--l">
      @for($i = 1; $i <= 6; $i++)
        <span class="ph ph--client" data-label="Client {{ $i }}"></span>
      @endfor
    </div>
  </div>
  <div class="marquee">
    <div class="marquee__track marquee__track--r">
      @for($i = 7; $i <= 12; $i++)
        <span class="ph ph--client" data-label="Client {{ $i }}"></span>
      @endfor
    </div>
  </div>
  @endif
</section>

<!-- ================= AGENCY SHOWCASES ================= -->
@foreach($agencies as $index => $agency)
@php
  $isEven   = $index % 2 === 0;
  $videos   = ($agency->media ?? collect())->where('type', 'video')->values();
  $images   = ($agency->media ?? collect())->where('type', 'image')->values();
  $websites = ($agency->media ?? collect())->where('type', 'website')->values();
@endphp

<section class="showcase showcase--{{ $agency->slug }}" id="{{ $agency->slug }}">
  @if($isEven)
  <div class="octo octo--recovery float">
    @if($setting('showcase_octo_image'))
      <img src="{{ asset($setting('showcase_octo_image')) }}" alt="" class="octo__img" aria-hidden="true">
    @else
      <span class="ph ph--octo" data-label="IMAGE — Octopus sitting"></span>
    @endif
  </div>
  @endif

  <div class="showcase__inner">
    @if($isEven)
    {{-- Even: content left, media right --}}
    <div class="showcase__left reveal">

      @if($agency->logo)
        <img src="{{ asset($agency->logo) }}" class="showcase__logo" alt="{{ $agency->{'name_' . $locale} }}">
      @else
        <span class="ph ph--wide showcase__logo" data-label="IMAGE — {{ $agency->name_en }} logo"></span>
      @endif

      <h3 class="showcase__heading uline-sm">
        {{ $agency->{'heading_' . $locale} ?? $agency->heading_en ?? $agency->{'name_' . $locale} }}
      </h3>

      <ul class="tick">
        @foreach($agency->services as $service)
          <li>{{ $service->{'title_' . $locale} ?? $service->title_en }}</li>
        @endforeach
      </ul>
    </div>

    <div class="showcase__right reveal">
      @include('front.includes.media_phones', compact('videos','images','websites','locale'))
    </div>

    @else
    {{-- Odd: media left, content right --}}
    <div class="showcase__right reveal">
      @include('front.includes.media_phones', compact('videos','images','websites','locale'))
    </div>

    <div class="showcase__left showcase__left--right reveal">

      @if($agency->logo)
        <img src="{{ asset($agency->logo) }}" class="showcase__logo showcase__logo--exp" alt="{{ $agency->{'name_' . $locale} }}">
      @else
        <span class="ph ph--sq showcase__logo showcase__logo--exp" data-label="IMAGE — {{ $agency->name_en }} logo"></span>
      @endif

      <h3 class="showcase__heading uline-sm">
        {{ $agency->{'heading_' . $locale} ?? $agency->heading_en ?? $agency->{'name_' . $locale} }}
      </h3>

      <ul class="tick tick--light">
        @foreach($agency->services as $service)
          <li>{{ $service->{'title_' . $locale} ?? $service->title_en }}</li>
        @endforeach
      </ul>
    </div>
    @endif
  </div>
</section>
@endforeach

<!-- ================= CTA ================= -->
<section class="cta" id="contact">
  <div class="octo octo--cta float">
    @if($setting('cta_octo_image'))
      <img src="{{ asset($setting('cta_octo_image')) }}" alt="" class="octo__img" aria-hidden="true">
    @else
      <span class="ph ph--octo-sign" data-label="IMAGE — Octopus holding AH.GROUP sign"></span>
    @endif
  </div>
  <div class="cta__copy reveal">
    <p class="cta__lead">{{ $setting('cta_lead') ?: __('front.cta_lead') }}</p>
    <a href="{{ $setting('cta_btn_link') ?: '#contact' }}" class="btn btn--navy btn--lg">
      {{ $setting('cta_btn_text') ?: __('front.cta_btn_text') }}
    </a>
  </div>
</section>

@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-tabs]').forEach(function (tabs) {
    tabs.querySelectorAll('.tabs__btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tab = this.dataset.tab;
            tabs.querySelectorAll('.tabs__btn').forEach(function (b) {
                b.classList.toggle('is-active', b === btn);
            });
            var parent = tabs.parentElement;
            parent.querySelectorAll('[data-panel]').forEach(function (panel) {
                panel.hidden = panel.dataset.panel !== tab;
            });
        });
    });
});
</script>
@endpush
