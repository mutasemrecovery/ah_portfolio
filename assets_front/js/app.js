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

  /* ---- Seamless marquee: duplicate each row's contents once ---- */
  document.querySelectorAll('.marquee__track').forEach(track => {
    track.innerHTML += track.innerHTML;
  });

  /* ---- Tabs (Videos / Images / Websites) ---- */
  document.querySelectorAll('[data-tabs]').forEach(group => {
    const btns = group.querySelectorAll('.tabs__btn');
    btns.forEach(btn => btn.addEventListener('click', () => {
      btns.forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      // subtle re-animate of the phones on tab change
      const phones = group.parentElement.querySelector('.phones');
      if (phones) {
        phones.animate(
          [{ opacity: .35, transform: 'translateY(10px)' }, { opacity: 1, transform: 'translateY(0)' }],
          { duration: 420, easing: 'ease-out' }
        );
      }
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

  /* ---- Phone carousel: cycles media through center phone ---- */
  const CAROUSEL_INTERVAL = 3200;

  document.querySelectorAll('[data-panel]').forEach(panel => {
    const allPhones = [...panel.querySelectorAll('.phone')];
    if (allPhones.length < 2) return;

    // Collect all media items (img / video / .phone-site div)
    const mediaEls = allPhones
      .map(p => p.querySelector('.phone-media, .ph--phone'))
      .filter(Boolean);

    if (mediaEls.length < 2) return;

    let idx = 0;
    let timer = null;

    const swapCenter = () => {
      idx = (idx + 1) % mediaEls.length;
      allPhones.forEach((phone, i) => {
        const srcIdx   = (i + idx) % mediaEls.length;
        const existing = phone.querySelector('.phone-media, .ph--phone');
        const source   = mediaEls[srcIdx];
        if (!existing || !source) return;

        existing.style.opacity = '0';
        setTimeout(() => {
          const clone = source.cloneNode(true);
          clone.style.opacity = '0';
          existing.replaceWith(clone);
          requestAnimationFrame(() => { clone.style.opacity = '1'; });
          if (clone.tagName === 'VIDEO') { clone.muted = true; clone.play?.(); }
        }, 340);
      });
    };

    const start = () => { if (!timer) timer = setInterval(swapCenter, CAROUSEL_INTERVAL); };
    const stop  = () => { clearInterval(timer); timer = null; };

    // Start only the initially visible panel
    if (panel.style.display !== 'none') start();

    // Sync with tab switching
    const showcase = panel.closest('.showcase__right, .showcase__left, [class*="showcase"]');
    if (showcase) {
      showcase.querySelectorAll('.tabs__btn').forEach(btn => {
        btn.addEventListener('click', () => {
          stop();
          if (panel.dataset.panel === btn.dataset.tab) start();
        });
      });
    }
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

    document.querySelectorAll('.phone').forEach(phone => {
      phone.addEventListener('click', () => {
        const img  = phone.querySelector('img.phone-media');
        const vid  = phone.querySelector('video.phone-media');
        const site = phone.querySelector('[data-href]') || phone.closest('[data-href]');

        let el;
        if (img) {
          el = document.createElement('img');
          el.src = img.src;
          el.alt = img.alt;
          el.className = 'lightbox__media';
        } else if (vid) {
          el = document.createElement('video');
          const s = vid.querySelector('source');
          if (s) { const ns = document.createElement('source'); ns.src = s.src; el.appendChild(ns); }
          else el.src = vid.src;
          el.controls = true; el.autoplay = true; el.className = 'lightbox__media';
        } else if (phone.dataset.href) {
          el = document.createElement('iframe');
          el.src = phone.dataset.href;
          el.className = 'lightbox__media lightbox__frame';
        }

        if (el) open(el);
      });
    });
  })();

});
