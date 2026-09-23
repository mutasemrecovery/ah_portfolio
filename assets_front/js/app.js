/* ============================================================
   AH.GROUP — interactions & animations
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {

  /* ---- Nav: shadow on scroll ---- */
  const nav = document.getElementById('nav');
  const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 8);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  /* ---- Mobile menu ---- */
  const burger = document.getElementById('burger');
  burger?.addEventListener('click', () => nav.classList.toggle('open'));
  document.querySelectorAll('.nav__links a, .nav__cta').forEach(a =>
    a.addEventListener('click', () => nav.classList.remove('open'))
  );

  /* ---- Active nav link on scroll ---- */
  const links = [...document.querySelectorAll('.nav__links a')];
  const sections = links
    .map(a => document.querySelector(a.getAttribute('href')))
    .filter(Boolean);
  const spy = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const id = '#' + e.target.id;
        links.forEach(l => l.classList.toggle('is-active', l.getAttribute('href') === id));
      }
    });
  }, { rootMargin: '-45% 0px -50% 0px' });
  sections.forEach(s => spy.observe(s));

  /* ---- Reveal on scroll ---- */
  const revealer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('in'); revealer.unobserve(e.target); }
    });
  }, { threshold: 0.15 });
  document.querySelectorAll('.reveal').forEach(el => revealer.observe(el));

  /* ---- Seamless marquee: even copies so translateX(-50%) loops cleanly ---- */
  document.querySelectorAll('.marquee__track').forEach(track => {
    const original = track.innerHTML;
    track.innerHTML += original;                    // 2× (minimum even)
    while (track.scrollWidth < window.innerWidth * 2) {
      track.innerHTML += original + original;       // +2 at a time → always even
    }
  });

  /* ---- Tabs (Videos / Images / Websites) ---- */
  document.querySelectorAll('[data-tabs]').forEach(group => {
    const btns = [...group.querySelectorAll('.tabs__btn')];
    const panels = [...group.parentElement.querySelectorAll('[data-panel]')];

    btns.forEach(btn => btn.addEventListener('click', () => {
      btns.forEach(b => b.classList.toggle('is-active', b === btn));

      panels.forEach(panel => {
        const isActive = panel.dataset.panel === btn.dataset.tab;
        panel.style.display = isActive ? 'flex' : 'none';
        panel.dispatchEvent(new CustomEvent(isActive ? 'phones:show' : 'phones:hide'));

        if (isActive) {
          panel.animate(
            [{ opacity: .35, transform: 'translateY(10px)' }, { opacity: 1, transform: 'translateY(0)' }],
            { duration: 420, easing: 'ease-out' }
          );
        }
      });
    }));
  });

  /* ---- Gentle parallax on the hero octopus ---- */
  const octo = document.querySelector('.octo--hero');
  if (octo && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      if (y < 800) octo.style.setProperty('translate', `0 ${y * -0.06}px`);
    }, { passive: true });
  }

  /* ---- Phone carousel: cycles media through the phone cluster ---- */
  const CAROUSEL_INTERVAL = 3200;

  document.querySelectorAll('[data-panel]').forEach(panel => {
    const phones = [...panel.querySelectorAll('.phone')];
    if (phones.length < 2) return;

    const allSlides = phones.map(phone => ({
      html: phone.innerHTML,
      href: phone.dataset.href || '',
      lightboxType: phone.dataset.lightboxType || '',
      lightboxSrc: phone.dataset.lightboxSrc || '',
      lightboxPoster: phone.dataset.lightboxPoster || '',
      lightboxTitle: phone.dataset.lightboxTitle || ''
    }));
    const realSlides = allSlides.filter(slide => slide.lightboxSrc);
    const slides = realSlides.length > 1 ? realSlides : allSlides;

    if (slides.length < 2) return;

    let idx = 0;
    let timer = null;

    const setDataset = (phone, slide) => {
      ['href', 'lightboxType', 'lightboxSrc', 'lightboxPoster', 'lightboxTitle'].forEach(key => {
        if (slide[key]) phone.dataset[key] = slide[key];
        else delete phone.dataset[key];
      });
    };

    const rotatePhones = () => {
      idx = (idx + 1) % slides.length;
      phones.forEach((phone, i) => {
        const slide = slides[(i + idx) % slides.length];
        const media = phone.querySelector('.phone-media, .ph--phone');
        if (media) media.style.opacity = '0';

        setTimeout(() => {
          phone.innerHTML = slide.html;
          setDataset(phone, slide);
          const nextMedia = phone.querySelector('.phone-media, .ph--phone');
          if (nextMedia) {
            nextMedia.style.opacity = '0';
            requestAnimationFrame(() => { nextMedia.style.opacity = '1'; });
          }
          phone.querySelectorAll('video').forEach(video => {
            video.muted = true;
            video.play?.();
          });
        }, 340);
      });
    };

    const start = () => { if (!timer) timer = setInterval(rotatePhones, CAROUSEL_INTERVAL); };
    const stop  = () => { clearInterval(timer); timer = null; };

    panel.addEventListener('mouseenter', stop);
    panel.addEventListener('mouseleave', () => { if (panel.style.display !== 'none') start(); });
    panel.addEventListener('phones:show', start);
    panel.addEventListener('phones:hide', stop);

    if (panel.style.display !== 'none') start();
  });

  /* ---- Lightbox ---- */
  (() => {
    const overlay = document.createElement('div');
    overlay.className = 'lightbox';
    overlay.innerHTML =
      '<button class="lightbox__close" aria-label="Close">&#x2715;</button>' +
      '<div class="lightbox__body"></div>';

    const lbBody  = overlay.querySelector('.lightbox__body');
    const lbClose = overlay.querySelector('.lightbox__close');

    const open = el => {
      lbBody.innerHTML = '';
      lbBody.appendChild(el);
      document.body.appendChild(overlay);
      document.body.style.overflow = 'hidden';
      requestAnimationFrame(() => overlay.classList.add('is-open'));
    };

    const close = () => {
      overlay.classList.remove('is-open');
      setTimeout(() => {
        overlay.remove();
        document.body.style.overflow = '';
      }, 260);
    };

    overlay.addEventListener('click', e => { if (e.target === overlay) close(); });
    lbClose.addEventListener('click', close);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });

    document.addEventListener('click', e => {
      const phone = e.target.closest('.phone[data-lightbox-src]');
      if (!phone) return;

      let el;
      if (phone.dataset.lightboxType === 'video') {
        el = document.createElement('video');
        el.src = phone.dataset.lightboxSrc;
        if (phone.dataset.lightboxPoster) el.poster = phone.dataset.lightboxPoster;
        el.controls = true;
        el.autoplay = true;
        el.playsInline = true;
        el.className = 'lightbox__media';
      } else if (phone.dataset.lightboxType === 'website') {
        el = document.createElement('iframe');
        el.src = phone.dataset.lightboxSrc;
        el.className = 'lightbox__media lightbox__frame';
      } else {
        el = document.createElement('img');
        el.src = phone.dataset.lightboxSrc;
        el.alt = phone.dataset.lightboxTitle || '';
        el.className = 'lightbox__media';
      }

      open(el);
    });
  })();

});
