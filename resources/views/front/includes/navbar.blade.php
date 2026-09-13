@php
    $locale      = app()->getLocale();
    $brandName   = isset($settings) ? (optional($settings['brand_name'] ?? null)->{'value_' . $locale} ?? 'AH.GROUP') : 'AH.GROUP';
    $navAgencies = isset($agencies) ? $agencies : collect();
@endphp

<header class="nav" id="nav">
  <div class="nav__inner">
    <a class="brand" href="#top">
      <span class="ph ph--logo brand__logo" data-label="LOGO — {{ $brandName }} mark" aria-label="{{ $brandName }} logo"></span>
      <span class="brand__name">{{ $brandName }}</span>
    </a>
    <nav class="nav__links" aria-label="{{ __('front.nav_aria_main') }}">
      <a href="#about" class="is-active">{{ __('front.nav_about') }}</a>
      <a href="#clients">{{ __('front.clients_title') }}</a>
      @foreach($navAgencies as $agency)
        <a href="#{{ $agency->slug }}">{{ strtoupper($agency->{'name_' . $locale} ?? $agency->name_en) }}</a>
      @endforeach
    </nav>
    <a href="#contact" class="btn btn--orange nav__cta">{{ __('front.nav_contact') }}</a>
    <button class="nav__burger" id="burger" aria-label="{{ __('front.nav_menu_aria') }}"><span></span><span></span><span></span></button>
  </div>
</header>
