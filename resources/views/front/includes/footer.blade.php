@php
    $locale = app()->getLocale();
    $s = fn(string $key) => isset($settings)
        ? optional($settings[$key] ?? null)->{'value_' . $locale} ?? (optional($settings[$key] ?? null)->value_en ?? '')
        : '';
    $brandName = $s('brand_name') ?: 'AH.GROUP';
    $logoPath = $s('logo_image') ?: '';
    $email = $s('email') ?: 'info@ahgroup.com';
    $phone = $s('phone') ?: '+962 771400000';
    $fbUrl = $s('facebook_url') ?: '#';
    $igUrl = $s('instagram_url') ?: '#';
    $webUrl = $s('website_url') ?: '#';
@endphp

<footer class="footer">
    <div class="footer__col footer__brand">
        @if ($logoPath)
            <img src="{{ asset($logoPath) }}" alt="{{ $brandName }} logo" class="footer__logo">
        @else
            <span class="ph ph--logo" data-label="LOGO"></span>
        @endif
        <span class="footer__name">{{ $brandName }}</span>
    </div>

    <div class="footer__col">
        <h4>{{ __('front.footer_contact') }}</h4>

        {{-- Email --}}
        <p class="footer__row">
            <span class="ico" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="5" width="18" height="14" rx="2" />
                    <path d="m3 7 9 6 9-6" />
                </svg>
            </span>

            <a href="mailto:{{ $email }}">
                {{ $email }}
            </a>
        </p>

        {{-- Phone --}}
        <p class="footer__row">
            <span class="ico" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M6.6 10.8a15 15 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25 11 11 0 0 0 3.5.56 1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1 11 11 0 0 0 .56 3.5 1 1 0 0 1-.25 1z" />
                </svg>
            </span>

            <a href="tel:{{ $phone }}">
                {{ $phone }}
            </a>
        </p>
    </div>

    <div class="footer__col">
        <h4>{{ __('front.footer_social') }}</h4>
        <div class="social">
            <a href="{{ $fbUrl }}" aria-label="Facebook">
                <svg viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M13.5 21v-8h2.6l.4-3h-3V8.1c0-.9.3-1.5 1.6-1.5H17V4c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3V10H7.5v3H10v8z" />
                </svg>
            </a>
            <a href="{{ $igUrl }}" aria-label="Instagram">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="3" width="18" height="18" rx="5" />
                    <circle cx="12" cy="12" r="4" />
                    <circle cx="17.3" cy="6.7" r="1" fill="currentColor" stroke="none" />
                </svg>
            </a>
            <a href="{{ $webUrl }}" aria-label="Website">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="12" r="9" />
                    <path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18" />
                </svg>
            </a>
        </div>
    </div>
</footer>
