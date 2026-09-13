<div class="tabs" data-tabs>
  <button class="tabs__btn is-active" data-tab="v">{{ __('front.tab_videos') }}</button>
  <button class="tabs__btn" data-tab="i">{{ __('front.tab_images') }}</button>
  <button class="tabs__btn" data-tab="w">{{ __('front.tab_websites') }}</button>
</div>

{{-- Videos panel --}}
<div class="phones" data-panel="v">
  @for($p = 0; $p < 5; $p++)
  @php $item = $videos->get($p); @endphp
  <div class="phone phone--{{ ['a','b','c','d','e'][$p] }} {{ $p === 2 ? 'play' : '' }}">
    @if($item && $item->thumbnail)
      <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
    @elseif($item && $item->file_path)
      <img src="{{ asset($item->file_path) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
    @else
      <span class="ph ph--phone" data-label="{{ $p === 2 ? __('front.media_featured') : __('front.media_item') }}"></span>
    @endif
  </div>
  @endfor
</div>

{{-- Images panel --}}
<div class="phones" data-panel="i" hidden>
  @for($p = 0; $p < 5; $p++)
  @php $item = $images->get($p); @endphp
  <div class="phone phone--{{ ['a','b','c','d','e'][$p] }} {{ $p === 2 ? 'play' : '' }}">
    @if($item && $item->file_path)
      <img src="{{ asset($item->file_path) }}" alt="{{ $item->{'title_' . $locale} }}" class="phone-media">
    @else
      <span class="ph ph--phone" data-label="{{ $p === 2 ? __('front.media_featured') : __('front.media_item') }}"></span>
    @endif
  </div>
  @endfor
</div>

{{-- Websites panel --}}
<div class="phones" data-panel="w" hidden>
  @for($p = 0; $p < 5; $p++)
  @php $item = $websites->get($p); @endphp
  <div class="phone phone--{{ ['a','b','c','d','e'][$p] }} {{ $p === 2 ? 'play' : '' }}">
    @if($item && $item->url)
      <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
         class="phone-media d-flex flex-column align-items-center justify-content-center gap-2 text-decoration-none">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" opacity=".6">
          <circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/>
        </svg>
        <span style="font-size:.65rem;opacity:.5;word-break:break-all;padding:0 8px;text-align:center">
          {{ parse_url($item->url, PHP_URL_HOST) ?? $item->url }}
        </span>
      </a>
    @else
      <span class="ph ph--phone" data-label="{{ $p === 2 ? __('front.media_featured') : __('front.media_item') }}"></span>
    @endif
  </div>
  @endfor
</div>
