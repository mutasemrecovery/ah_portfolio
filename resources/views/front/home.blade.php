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
      <span class="ph ph--sign" data-label="IMAGE — Sign board with AH.GROUP logo"></span>
    </div>
    <div class="octo octo--hero float">
      <span class="ph ph--octo" data-label="IMAGE — Octopus mascot (orange hoodie)"></span>
    </div>
  </div>

  <div class="hero__copy reveal">
    <h1 class="display">{{ $hero?->{'title_' . $locale} ?? $hero?->title_en ?? 'House Of Brands' }}</h1>
    <p class="hero__sub">{{ $hero?->{'subtitle_' . $locale} ?? $hero?->subtitle_en ?? 'We Empower Innovation, Marketing & Tech' }}</p>
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
      <p class="agcard__desc">A SPECIALIZED COMPANY OFFERING COMPREHENSIVE DIGITAL SERVICES</p>
    </article>
    @endforelse
  </div>

  <div class="octo octo--peek float">
    <span class="ph ph--octo" data-label="IMAGE — Octopus peeking (front)"></span>
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
      <h2 class="about__title">{{ $about?->{'title_' . $locale} ?? $about?->title_en ?? 'About US' }}</h2>
      @if($about)
        @foreach(explode("\n", trim($about->{'body_' . $locale} ?? $about->body_en)) as $para)
          @if(trim($para))
            <p>{{ trim($para) }}</p>
          @endif
        @endforeach
      @else
        <p>AH GROUP is a leading group of companies established in Jordan with the purpose of uniting specialized companies under one umbrella, offering integrated solutions in marketing, technology, production, and business entrepreneurship.</p>
        <p>The group includes successful companies such as Recovery Jo and Experts World for Marketing, and continuously seeks expansion by launching new companies in various sectors.</p>
        <p>We believe the group's strength lies in its diversity, service integration, and teamwork that combines innovation, high performance, and strategic vision.</p>
      @endif
    </div>
  </div>

  <div class="octo octo--about float">
    <span class="ph ph--octo" data-label="IMAGE — Octopus sitting"></span>
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
@php $isEven = $index % 2 === 0; @endphp

<section class="showcase showcase--{{ $agency->slug }}" id="{{ $agency->slug }}">
  @if($isEven)
  <div class="octo octo--recovery float">
    <span class="ph ph--octo" data-label="IMAGE — Octopus sitting"></span>
  </div>
  @endif

  <div class="showcase__inner">
    @if($isEven)
    <div class="showcase__left reveal">
    @else
    <div class="showcase__right reveal">
      <div class="tabs" data-tabs>
        <button class="tabs__btn is-active" data-tab="v">{{ __('front.tab_videos') }}</button>
        <button class="tabs__btn" data-tab="i">{{ __('front.tab_images') }}</button>
        <button class="tabs__btn" data-tab="w">{{ __('front.tab_websites') }}</button>
      </div>
      <div class="phones">
        @php $agencyMedia = $agency->media ?? collect(); @endphp
        @for($p = 0; $p < 5; $p++)
        @php $item = $agencyMedia->get($p); @endphp
        <div class="phone phone--{{ ['a','b','c','d','e'][$p] }} {{ $p === 2 ? 'play' : '' }}">
          @if($item && $item->file_path)
            <img src="{{ asset($item->file_path) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
          @elseif($item && $item->thumbnail)
            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
          @else
            <span class="ph ph--phone" data-label="{{ $p === 2 ? 'Featured media' : 'Media' }}"></span>
          @endif
        </div>
        @endfor
      </div>
    </div>
    <div class="showcase__left showcase__left--right reveal">
    @endif

      @if($agency->logo)
        <img src="{{ asset($agency->logo) }}" class="showcase__logo {{ !$isEven ? 'showcase__logo--exp' : '' }}" alt="{{ $agency->{'name_' . $locale} }}">
      @else
        <span class="ph ph--{{ $isEven ? 'wide' : 'sq' }} showcase__logo {{ !$isEven ? 'showcase__logo--exp' : '' }}"
              data-label="IMAGE — {{ $agency->name_en }} logo"></span>
      @endif

      <h3 class="showcase__heading uline-sm">
        {{ $agency->{'heading_' . $locale} ?? $agency->heading_en ?? $agency->{'name_' . $locale} }}
      </h3>

      <ul class="tick {{ !$isEven ? 'tick--light' : '' }}">
        @foreach($agency->services as $service)
          <li>{{ $service->{'title_' . $locale} ?? $service->title_en }}</li>
        @endforeach
      </ul>
    </div>

    @if($isEven)
    <div class="showcase__right reveal">
      <div class="tabs" data-tabs>
        <button class="tabs__btn is-active" data-tab="v">{{ __('front.tab_videos') }}</button>
        <button class="tabs__btn" data-tab="i">{{ __('front.tab_images') }}</button>
        <button class="tabs__btn" data-tab="w">{{ __('front.tab_websites') }}</button>
      </div>
      <div class="phones">
        @php $agencyMedia = $agency->media ?? collect(); @endphp
        @for($p = 0; $p < 5; $p++)
        @php $item = $agencyMedia->get($p); @endphp
        <div class="phone phone--{{ ['a','b','c','d','e'][$p] }} {{ $p === 2 ? 'play' : '' }}">
          @if($item && $item->file_path)
            <img src="{{ asset($item->file_path) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
          @elseif($item && $item->thumbnail)
            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
          @else
            <span class="ph ph--phone" data-label="{{ $p === 2 ? 'Featured media' : 'Media' }}"></span>
          @endif
        </div>
        @endfor
      </div>
    </div>
    @endif
  </div>
</section>
@endforeach

<!-- ================= CTA ================= -->
<section class="cta" id="contact">
  <div class="octo octo--cta float">
    <span class="ph ph--octo-sign" data-label="IMAGE — Octopus holding AH.GROUP sign"></span>
  </div>
  <div class="cta__copy reveal">
    <p class="cta__lead">{{ $setting('cta_lead') ?: __('front.cta_lead') }}</p>
    <a href="{{ $setting('cta_btn_link') ?: '#contact' }}" class="btn btn--navy btn--lg">
      {{ $setting('cta_btn_text') ?: __('front.cta_btn_text') }}
    </a>
  </div>
</section>

@endsection
