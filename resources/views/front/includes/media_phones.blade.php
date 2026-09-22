<div class="tabs" data-tabs>
  <button class="tabs__btn is-active" data-tab="v">{{ __('front.tab_videos') }}</button>
  <button class="tabs__btn" data-tab="i">{{ __('front.tab_images') }}</button>
  <button class="tabs__btn" data-tab="vi">{{ __('front.tab_visual_identity') }}</button>
  <button class="tabs__btn" data-tab="w">{{ __('front.tab_websites') }}</button>
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

{{-- Visual Identity panel shared by Recovery and Experts --}}
<div class="phones" data-panel="vi" style="display:none">
  @for($p = 0; $p < 5; $p++)
  @php $item = ($visualIdentities ?? collect())->get($p); @endphp
  <div class="phone phone--{{ ['a','b','c','d','e'][$p] }} {{ $p === 2 ? 'play' : '' }}"
       @if($item && $item->file_path)
         data-lightbox-type="image"
         data-lightbox-src="{{ asset($item->file_path) }}"
         data-lightbox-title="{{ $item->{'title_' . $locale} ?? 'Visual Identity' }}"
       @endif>
    @if($item && $item->file_path)
      <img src="{{ asset($item->file_path) }}" alt="{{ $item->{'title_' . $locale} ?? 'Visual Identity' }}" class="phone-media">
    @else
      <span class="ph ph--phone" data-label="Visual Identity"></span>
    @endif
  </div>
  @endfor
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
