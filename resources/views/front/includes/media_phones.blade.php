<div class="tabs" data-tabs>
  <button class="tabs__btn is-active" data-tab="v">{{ __('front.tab_videos') }}</button>
  <button class="tabs__btn" data-tab="i">{{ __('front.tab_images') }}</button>
  <button class="tabs__btn" data-tab="vi">{{ __('front.tab_visual_identity') }}</button>
  @if($websites->isNotEmpty())
  <button class="tabs__btn" data-tab="w">{{ __('front.tab_websites') }}</button>
  @endif
</div>

{{-- Videos panel --}}
<div class="phones" data-panel="v">
  @for($p = 0; $p < 5; $p++)
  @php
    $item      = $videos->get($p);
    $isVideo   = $item && $item->file_path &&
                 preg_match('/\.(mp4|webm|mov|ogg|avi)$/i', $item->file_path);
  @endphp
  <div class="phone phone--{{ ['a','b','c','d','e'][$p] }} {{ $p === 2 ? 'play' : '' }}"
       @if($item && $item->file_path)
         data-lightbox-type="{{ $isVideo ? 'video' : 'image' }}"
         data-lightbox-src="{{ asset($item->file_path) }}"
         @if($item->thumbnail) data-lightbox-poster="{{ asset($item->thumbnail) }}" @endif
         data-lightbox-title="{{ $item->{'title_' . $locale} ?? '' }}"
       @endif>
    @if($item && $item->thumbnail)
      {{-- Proper thumbnail image uploaded → always use it --}}
      <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
    @elseif($isVideo)
      {{-- No thumbnail but we have a real video file → use <video> --}}
      <video class="phone-media" autoplay muted loop playsinline preload="metadata">
        <source src="{{ asset($item->file_path) }}">
      </video>
    @elseif($item && $item->file_path)
      {{-- file_path is an image (poster/cover) --}}
      <img src="{{ asset($item->file_path) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
    @else
      <span class="ph ph--phone" data-label="{{ $p === 2 ? __('front.media_featured') : __('front.media_item') }}"></span>
    @endif
  </div>
  @endfor
</div>

{{-- Images panel --}}
<div class="phones" data-panel="i" style="display:none">
  @for($p = 0; $p < 5; $p++)
  @php $item = $images->get($p); @endphp
  <div class="phone phone--{{ ['a','b','c','d','e'][$p] }} {{ $p === 2 ? 'play' : '' }}"
       @if($item && $item->file_path)
         data-lightbox-type="image"
         data-lightbox-src="{{ asset($item->file_path) }}"
         data-lightbox-title="{{ $item->{'title_' . $locale} ?? '' }}"
       @endif>
    @if($item && $item->file_path)
      <img src="{{ asset($item->file_path) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
    @else
      <span class="ph ph--phone" data-label="{{ $p === 2 ? __('front.media_featured') : __('front.media_item') }}"></span>
    @endif
  </div>
  @endfor
</div>

{{-- Visual Identity panel — PDF Cards --}}
@php $viItems = $visualIdentities ?? collect(); @endphp
<div class="pdf-shelf" data-panel="vi" style="display:none">
  @if($viItems->isNotEmpty())

    @foreach($viItems as $i => $item)
    <div class="pdf-card {{ $i > 0 ? 'pdf-card--hidden' : '' }}"
         data-lightbox-type="website"
         data-lightbox-src="{{ asset($item->file_path) }}"
         data-lightbox-title="{{ $item->{'title_' . $locale} ?? $item->title_en ?? '' }}">
      <div class="pdf-card__page">
        <div class="pdf-card__fold"></div>
        <div class="pdf-card__lines">
          <span></span><span></span><span></span>
          <span class="pdf-card__line--s"></span>
          <span></span><span></span>
          <span class="pdf-card__line--s"></span>
          <span></span>
        </div>
        <div class="pdf-card__badge">PDF</div>
        <div class="pdf-card__overlay">
          <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          <span>اضغط لفتح الملف</span>
        </div>
      </div>
      @if($item->{'title_' . $locale} ?? $item->title_en)
        <p class="pdf-card__title">{{ $item->{'title_' . $locale} ?? $item->title_en }}</p>
      @endif
    </div>
    @endforeach

    @if($viItems->count() > 1)
    <div class="pdf-shelf__nav">
      <button class="pdf-shelf__btn pdf-shelf__btn--prev" aria-label="السابق">&#x2039;</button>
      <span class="pdf-shelf__counter">1 / {{ $viItems->count() }}</span>
      <button class="pdf-shelf__btn pdf-shelf__btn--next" aria-label="التالي">&#x203A;</button>
    </div>
    @endif

  @else
    <div class="pdf-empty">
      <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" opacity=".35"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      <p>لا توجد ملفات Visual Identity</p>
    </div>
  @endif
</div>

{{-- Websites panel --}}
<div class="phones" data-panel="w" style="display:none">
  @for($p = 0; $p < 5; $p++)
  @php $item = $websites->get($p); @endphp
  <div class="phone phone--{{ ['a','b','c','d','e'][$p] }} {{ $p === 2 ? 'play' : '' }}"
       @if($item && $item->url)
         data-href="{{ $item->url }}"
         data-lightbox-type="website"
         data-lightbox-src="{{ $item->url }}"
         data-lightbox-title="{{ $item->{'title_' . $locale} ?? '' }}"
       @endif>
    @if($item && $item->thumbnail)
      {{-- Screenshot/thumbnail uploaded for this website --}}
      <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->{'title_' . $locale} ?? '' }}" class="phone-media">
    @elseif($item && $item->url)
      {{-- No thumbnail: show domain + globe icon --}}
      <div class="phone-media phone-site">
        <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" opacity=".5">
          <circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/>
        </svg>
        <span class="phone-site__domain">{{ parse_url($item->url, PHP_URL_HOST) ?? $item->url }}</span>
      </div>
    @else
      <span class="ph ph--phone" data-label="{{ $p === 2 ? __('front.media_featured') : __('front.media_item') }}"></span>
    @endif
  </div>
  @endfor
</div>
