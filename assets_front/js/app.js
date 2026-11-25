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
  let lbOpen = null; // assigned by lightbox IIFE below

  document.querySelectorAll('[data-panel]').forEach(panel => {
    const phones = [...panel.querySelectorAll('.phone')];
    if (phones.length < 2) return;

    const buildPhoneHtml = slide => {
      if (slide.thumbnail) {
        return `<img src="${slide.thumbnail}" alt="${slide.lightboxTitle || ''}" class="phone-media">`;
      }
      if (slide.domain) {
        return `<div class="phone-media phone-site">` +
          `<svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" opacity=".5">` +
          `<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/>` +
          `</svg><span class="phone-site__domain">${slide.domain}</span></div>`;
      }
      return `<span class="ph ph--phone"></span>`;
    };

    let slides;
    if (panel.dataset.allSlides) {
      try {
        const all = JSON.parse(panel.dataset.allSlides);
        if (all.length > 0) slides = all;
      } catch (e) {}
    }
    if (!slides) {
      const domSlides = phones.map(phone => ({
        html:           phone.innerHTML,
        href:           phone.dataset.href || '',
        lightboxType:   phone.dataset.lightboxType || '',
        lightboxSrc:    phone.dataset.lightboxSrc || '',
        lightboxPoster: phone.dataset.lightboxPoster || '',
        lightboxTitle:  phone.dataset.lightboxTitle || ''
      }));
      const real = domSlides.filter(s => s.lightboxSrc);
      slides = real.length > 1 ? real : domSlides;
    }

    if (slides.length < 2) return;

    const allServerSlides = slides.slice();
    panel._slides = slides.slice(); // tracked for video grid
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
          phone.innerHTML = slide.html !== undefined ? slide.html : buildPhoneHtml(slide);
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

    let panelSector = 'all';
    let panelSearch = '';

    const applyPanelFilter = () => {
      stop();
      const q = panelSearch.toLowerCase().trim();
      const filtered = allServerSlides.filter(s => {
        const sectorMatch = panelSector === 'all' || !s.sector || s.sector === panelSector;
        const titleMatch  = !q || (s.lightboxTitle || '').toLowerCase().includes(q);
        return sectorMatch && titleMatch;
      });
      slides = filtered.length > 0 ? filtered : [];
      panel._slides = slides.slice();
      idx = 0;
      if (slides.length === 0) {
        phones.forEach(phone => {
          phone.innerHTML = '<span class="ph ph--phone"></span>';
          ['href', 'lightboxType', 'lightboxSrc', 'lightboxPoster', 'lightboxTitle'].forEach(k => delete phone.dataset[k]);
        });
        return;
      }
      phones.forEach((phone, i) => {
        const slide = slides[i % slides.length];
        phone.innerHTML = slide.html !== undefined ? slide.html : buildPhoneHtml(slide);
        setDataset(phone, slide);
        const m = phone.querySelector('.phone-media, .ph--phone');
        if (m) { m.style.opacity = '0'; requestAnimationFrame(() => { m.style.opacity = '1'; }); }
      });
      if (slides.length > 1 && panel.style.display !== 'none') start();
    };

    panel.addEventListener('sector:filter', e => { panelSector = e.detail.sector; applyPanelFilter(); });
    panel.addEventListener('search:filter', e => { panelSearch = e.detail.query || ''; applyPanelFilter(); });

    if (panel.style.display !== 'none') start();
  });

  /* ---- PDF Cards navigation (Visual Identity) ---- */
  document.querySelectorAll('.pdf-shelf').forEach(shelf => {
    const allCards = [...shelf.querySelectorAll('.pdf-card')];
    const counter  = shelf.querySelector('.pdf-shelf__counter');
    const nav      = shelf.querySelector('.pdf-shelf__nav');

    let visibleCards = allCards;
    let idx = 0;

    const show = i => {
      if (visibleCards.length === 0) return;
      idx = ((i % visibleCards.length) + visibleCards.length) % visibleCards.length;
      allCards.forEach(c => c.classList.add('pdf-card--hidden'));
      visibleCards[idx].classList.remove('pdf-card--hidden');
      if (counter) counter.textContent = `${idx + 1} / ${visibleCards.length}`;
      if (nav) nav.style.display = visibleCards.length > 1 ? '' : 'none';
    };

    shelf.querySelector('.pdf-shelf__btn--prev')?.addEventListener('click', () => show(idx - 1));
    shelf.querySelector('.pdf-shelf__btn--next')?.addEventListener('click', () => show(idx + 1));

    let shelfSector = 'all';
    let shelfSearch = '';

    const applyShelfFilter = () => {
      const q = shelfSearch.toLowerCase().trim();
      visibleCards = allCards.filter(c => {
        const sectorMatch = shelfSector === 'all' || !c.dataset.sector || c.dataset.sector === shelfSector;
        const titleMatch  = !q || c.textContent.toLowerCase().includes(q);
        return sectorMatch && titleMatch;
      });
      if (visibleCards.length === 0) {
        allCards.forEach(c => c.classList.add('pdf-card--hidden'));
        if (counter) counter.textContent = '';
        if (nav) nav.style.display = 'none';
      } else {
        show(0);
      }
    };

    shelf.addEventListener('sector:filter', e => { shelfSector = e.detail.sector; applyShelfFilter(); });
    shelf.addEventListener('search:filter', e => { shelfSearch = e.detail.query || ''; applyShelfFilter(); });

    show(0);
  });

  /* ---- Video grid: slideToFakePhone converts raw slides to lightbox-compatible items ---- */
  const slideToFakePhone = slide => ({
    dataset: {
      lightboxType:   slide.lightboxType   || '',
      lightboxSrc:    slide.lightboxSrc    || '',
      lightboxPoster: slide.lightboxPoster || slide.thumbnail || '',
      lightboxTitle:  slide.lightboxTitle  || '',
      href:           slide.href           || '',
    }
  });

  const openVideoGrid = (panel, initialSearch = '') => {
    const vidSlides = (panel._slides || []).filter(
      s => s.lightboxSrc && (s.lightboxType === 'youtube' || s.lightboxType === 'video')
    );
    if (vidSlides.length === 0 || !lbOpen) return;

    const overlay = document.createElement('div');
    overlay.className = 'vgrid';

    const vgridSectors = [...new Set(vidSlides.map(s => s.sector).filter(Boolean))].sort();
    const sectorsBar = vgridSectors.length
      ? '<div class="vgrid__sectors">' +
          '<button class="vgrid__spill is-active" data-vsector="all">الكل</button>' +
          vgridSectors.map(sec => `<button class="vgrid__spill" data-vsector="${sec}">${sec}</button>`).join('') +
        '</div>'
      : '';

    overlay.innerHTML =
      '<div class="vgrid__header">' +
        '<h3 class="vgrid__title">فيديوهات</h3>' +
        '<div class="vgrid__search-wrap">' +
          '<svg class="vgrid__search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">' +
            '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>' +
          '</svg>' +
          '<input type="search" class="vgrid__search" placeholder="ابحث عن فيديو...">' +
        '</div>' +
        '<button class="vgrid__close" aria-label="Close">&#x2715;</button>' +
      '</div>' +
      sectorsBar +
      '<div class="vgrid__body">' +
        '<div class="vgrid__grid">' +
          vidSlides.map((s, i) =>
            `<button class="vgrid__card" data-idx="${i}" data-title="${(s.lightboxTitle || '').toLowerCase()}" data-sector="${s.sector || ''}" type="button">` +
              `<div class="vgrid__thumb-wrap">` +
                (s.thumbnail
                  ? `<img src="${s.thumbnail}" alt="" class="vgrid__thumb" loading="lazy">`
                  : `<div class="vgrid__thumb"></div>`) +
                `<div class="vgrid__play">` +
                  `<div class="vgrid__play-icon">` +
                    `<svg viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>` +
                  `</div>` +
                `</div>` +
              `</div>` +
              (s.lightboxTitle ? `<p class="vgrid__label">${s.lightboxTitle}</p>` : '') +
            `</button>`
          ).join('') +
        '</div>' +
        '<p class="vgrid__empty" hidden>لا توجد نتائج</p>' +
      '</div>';

    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => overlay.classList.add('is-open'));

    const searchInput  = overlay.querySelector('.vgrid__search');
    const cards        = [...overlay.querySelectorAll('.vgrid__card')];
    const emptyMsg     = overlay.querySelector('.vgrid__empty');
    const spills       = [...overlay.querySelectorAll('.vgrid__spill')];
    let searchTimer    = null;
    let activeSector   = 'all';

    const filterCards = (query, sector) => {
      const q = (query || '').toLowerCase().trim();
      if (sector !== undefined) activeSector = sector;
      let visible = 0;
      cards.forEach(card => {
        const titleMatch  = !q || card.dataset.title.includes(q);
        const sectorMatch = activeSector === 'all' || card.dataset.sector === activeSector;
        const match = titleMatch && sectorMatch;
        card.style.display = match ? '' : 'none';
        if (match) visible++;
      });
      emptyMsg.hidden = visible > 0;
    };

    spills.forEach(pill => {
      pill.addEventListener('click', () => {
        spills.forEach(p => p.classList.remove('is-active'));
        pill.classList.add('is-active');
        filterCards(searchInput.value, pill.dataset.vsector);
      });
    });

    searchInput.addEventListener('input', () => {
      const q = searchInput.value;
      filterCards(q);
      clearTimeout(searchTimer);
      searchTimer = setTimeout(() => {
        const url = new URL(window.location);
        if (q.trim()) url.searchParams.set('search', q);
        else url.searchParams.delete('search');
        history.replaceState(history.state, '', url);
      }, 350);
    });

    if (initialSearch) { searchInput.value = initialSearch; filterCards(initialSearch); }

    const closeGrid = (keepOverflow = false) => {
      // Clean search param from URL on close
      const url = new URL(window.location);
      url.searchParams.delete('search');
      history.replaceState(history.state, '', url);

      overlay.classList.remove('is-open');
      setTimeout(() => {
        overlay.remove();
        if (!keepOverflow) document.body.style.overflow = '';
      }, 260);
    };

    overlay.querySelector('.vgrid__close').addEventListener('click', () => closeGrid());

    document.addEventListener('keydown', function escGrid(e) {
      if (e.key === 'Escape') { closeGrid(); document.removeEventListener('keydown', escGrid); }
    });

    overlay.querySelectorAll('.vgrid__card').forEach(card => {
      card.addEventListener('click', () => {
        const startIdx = parseInt(card.dataset.idx);
        closeGrid(true); // keep overflow hidden — lightbox takes over
        setTimeout(() => lbOpen(vidSlides.map(slideToFakePhone), startIdx), 270);
      });
    });
  };

  /* ---- Image grid overlay ---- */
  const openImageGrid = (panel, initialSearch = '') => {
    const imgSlides = (panel._slides || []).filter(
      s => s.lightboxSrc && s.lightboxType === 'image'
    );
    if (imgSlides.length === 0 || !lbOpen) return;

    const overlay = document.createElement('div');
    overlay.className = 'vgrid';

    const igSectors = [...new Set(imgSlides.map(s => s.sector).filter(Boolean))].sort();
    const sectorsBar = igSectors.length
      ? '<div class="vgrid__sectors">' +
          '<button class="vgrid__spill is-active" data-vsector="all">الكل</button>' +
          igSectors.map(sec => `<button class="vgrid__spill" data-vsector="${sec}">${sec}</button>`).join('') +
        '</div>'
      : '';

    overlay.innerHTML =
      '<div class="vgrid__header">' +
        '<h3 class="vgrid__title">صور</h3>' +
        '<div class="vgrid__search-wrap">' +
          '<svg class="vgrid__search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">' +
            '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>' +
          '</svg>' +
          '<input type="search" class="vgrid__search" placeholder="ابحث عن صورة...">' +
        '</div>' +
        '<button class="vgrid__close" aria-label="Close">&#x2715;</button>' +
      '</div>' +
      sectorsBar +
      '<div class="vgrid__body">' +
        '<div class="vgrid__grid">' +
          imgSlides.map((s, i) =>
            `<button class="vgrid__card" data-idx="${i}" data-title="${(s.lightboxTitle || '').toLowerCase()}" data-sector="${s.sector || ''}" type="button">` +
              `<div class="vgrid__thumb-wrap">` +
                `<img src="${s.lightboxSrc}" alt="${s.lightboxTitle || ''}" class="vgrid__thumb" loading="lazy">` +
                `<div class="vgrid__play">` +
                  `<div class="vgrid__play-icon">` +
                    `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>` +
                  `</div>` +
                `</div>` +
              `</div>` +
              (s.lightboxTitle ? `<p class="vgrid__label">${s.lightboxTitle}</p>` : '') +
            `</button>`
          ).join('') +
        '</div>' +
        '<p class="vgrid__empty" hidden>لا توجد نتائج</p>' +
      '</div>';

    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => overlay.classList.add('is-open'));

    const searchInput = overlay.querySelector('.vgrid__search');
    const cards       = [...overlay.querySelectorAll('.vgrid__card')];
    const emptyMsg    = overlay.querySelector('.vgrid__empty');
    const spills      = [...overlay.querySelectorAll('.vgrid__spill')];
    let activeSector  = 'all';

    const filterCards = (query, sector) => {
      const q = (query || '').toLowerCase().trim();
      if (sector !== undefined) activeSector = sector;
      let visible = 0;
      cards.forEach(card => {
        const match = (!q || card.dataset.title.includes(q)) &&
                      (activeSector === 'all' || card.dataset.sector === activeSector);
        card.style.display = match ? '' : 'none';
        if (match) visible++;
      });
      emptyMsg.hidden = visible > 0;
    };

    spills.forEach(pill => {
      pill.addEventListener('click', () => {
        spills.forEach(p => p.classList.remove('is-active'));
        pill.classList.add('is-active');
        filterCards(searchInput.value, pill.dataset.vsector);
      });
    });

    searchInput.addEventListener('input', () => filterCards(searchInput.value));
    if (initialSearch) { searchInput.value = initialSearch; filterCards(initialSearch); }

    const closeGrid = () => {
      overlay.classList.remove('is-open');
      setTimeout(() => { overlay.remove(); document.body.style.overflow = ''; }, 260);
    };

    overlay.querySelector('.vgrid__close').addEventListener('click', closeGrid);
    document.addEventListener('keydown', function escGrid(e) {
      if (e.key === 'Escape') { closeGrid(); document.removeEventListener('keydown', escGrid); }
    });

    overlay.querySelectorAll('.vgrid__card').forEach(card => {
      card.addEventListener('click', () => {
        const startIdx = parseInt(card.dataset.idx);
        closeGrid();
        setTimeout(() => lbOpen(imgSlides.map(slideToFakePhone), startIdx), 270);
      });
    });
  };

  /* ---- Website grid overlay ---- */
  const openWebsiteGrid = (panel) => {
    const webSlides = (panel._slides || []).filter(s => s.lightboxSrc && s.lightboxType === 'website');
    if (webSlides.length === 0) return;

    const overlay = document.createElement('div');
    overlay.className = 'vgrid';

    const wgSectors = [...new Set(webSlides.map(s => s.sector).filter(Boolean))].sort();
    const sectorsBar = wgSectors.length
      ? '<div class="vgrid__sectors">' +
          '<button class="vgrid__spill is-active" data-vsector="all">الكل</button>' +
          wgSectors.map(sec => `<button class="vgrid__spill" data-vsector="${sec}">${sec}</button>`).join('') +
        '</div>'
      : '';

    overlay.innerHTML =
      '<div class="vgrid__header">' +
        '<h3 class="vgrid__title">مواقع</h3>' +
        '<div class="vgrid__search-wrap">' +
          '<svg class="vgrid__search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">' +
            '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>' +
          '</svg>' +
          '<input type="search" class="vgrid__search" placeholder="ابحث عن موقع...">' +
        '</div>' +
        '<button class="vgrid__close" aria-label="Close">&#x2715;</button>' +
      '</div>' +
      sectorsBar +
      '<div class="vgrid__body">' +
        '<div class="vgrid__grid">' +
          webSlides.map((s, i) => {
            const domain = s.domain || s.lightboxSrc.replace(/^https?:\/\//, '').split('/')[0];
            const thumb  = s.thumbnail
              ? `<img src="${s.thumbnail}" alt="${s.lightboxTitle || ''}" class="vgrid__thumb" loading="lazy">`
              : `<div class="vgrid__thumb vgrid__thumb--site">` +
                  `<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" opacity=".5"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></svg>` +
                  `<span class="vgrid__site-domain">${domain}</span>` +
                `</div>`;
            return `<a class="vgrid__card" data-idx="${i}" data-title="${(s.lightboxTitle || domain).toLowerCase()}" data-sector="${s.sector || ''}" href="${s.lightboxSrc}" target="_blank" rel="noopener">` +
              `<div class="vgrid__thumb-wrap">${thumb}` +
                `<div class="vgrid__play">` +
                  `<div class="vgrid__play-icon">` +
                    `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>` +
                  `</div>` +
                `</div>` +
              `</div>` +
              (s.lightboxTitle ? `<p class="vgrid__label">${s.lightboxTitle}</p>` : `<p class="vgrid__label">${domain}</p>`) +
            `</a>`;
          }).join('') +
        '</div>' +
        '<p class="vgrid__empty" hidden>لا توجد نتائج</p>' +
      '</div>';

    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => overlay.classList.add('is-open'));

    const searchInput = overlay.querySelector('.vgrid__search');
    const cards       = [...overlay.querySelectorAll('.vgrid__card')];
    const emptyMsg    = overlay.querySelector('.vgrid__empty');
    const spills      = [...overlay.querySelectorAll('.vgrid__spill')];
    let activeSector  = 'all';

    const filterCards = (query, sector) => {
      const q = (query || '').toLowerCase().trim();
      if (sector !== undefined) activeSector = sector;
      let visible = 0;
      cards.forEach(card => {
        const match = (!q || card.dataset.title.includes(q)) &&
                      (activeSector === 'all' || card.dataset.sector === activeSector);
        card.style.display = match ? '' : 'none';
        if (match) visible++;
      });
      emptyMsg.hidden = visible > 0;
    };

    spills.forEach(pill => {
      pill.addEventListener('click', () => {
        spills.forEach(p => p.classList.remove('is-active'));
        pill.classList.add('is-active');
        filterCards(searchInput.value, pill.dataset.vsector);
      });
    });

    searchInput.addEventListener('input', () => filterCards(searchInput.value));

    const closeGrid = () => {
      overlay.classList.remove('is-open');
      setTimeout(() => { overlay.remove(); document.body.style.overflow = ''; }, 260);
    };

    overlay.querySelector('.vgrid__close').addEventListener('click', closeGrid);
    document.addEventListener('keydown', function escGrid(e) {
      if (e.key === 'Escape') { closeGrid(); document.removeEventListener('keydown', escGrid); }
    });

    // links open in new tab natively — just close grid on click
    cards.forEach(card => card.addEventListener('click', () => setTimeout(closeGrid, 80)));
  };

  /* ---- Lightbox with prev/next navigation ---- */
  (() => {
    const overlay = document.createElement('div');
    overlay.className = 'lightbox';
    overlay.innerHTML =
      '<button class="lightbox__close"  aria-label="Close">&#x2715;</button>' +
      '<button class="lightbox__prev"   aria-label="Previous">&#x2039;</button>' +
      '<div class="lightbox__body"></div>' +
      '<button class="lightbox__next"   aria-label="Next">&#x203A;</button>' +
      '<span  class="lightbox__counter"></span>';

    const lbBody    = overlay.querySelector('.lightbox__body');
    const lbClose   = overlay.querySelector('.lightbox__close');
    const lbPrev    = overlay.querySelector('.lightbox__prev');
    const lbNext    = overlay.querySelector('.lightbox__next');
    const lbCounter = overlay.querySelector('.lightbox__counter');

    let items = [];
    let idx   = 0;

    const ytId = url => {
      const m = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([^&\s?#]+)/);
      return m ? m[1] : null;
    };

    const buildEl = phone => {
      let el;
      if (phone.dataset.lightboxType === 'youtube') {
        const id = ytId(phone.dataset.lightboxSrc);
        el = document.createElement('iframe');
        el.src = `https://www.youtube-nocookie.com/embed/${id}?autoplay=1&rel=0&modestbranding=1&showinfo=0&iv_load_policy=3&color=white`;
        el.allow = 'autoplay; fullscreen; encrypted-media; picture-in-picture';
        el.allowFullscreen = true;
        el.className = 'lightbox__media lightbox__frame';
      } else if (phone.dataset.lightboxType === 'video') {
        el = document.createElement('video');
        el.src = phone.dataset.lightboxSrc;
        if (phone.dataset.lightboxPoster) el.poster = phone.dataset.lightboxPoster;
        el.controls = true; el.autoplay = true; el.playsInline = true;
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
      return el;
    };

    const show = i => {
      idx = (i + items.length) % items.length;
      lbBody.innerHTML = '';
      lbBody.appendChild(buildEl(items[idx]));
      const multi = items.length > 1;
      lbPrev.style.display    = multi ? '' : 'none';
      lbNext.style.display    = multi ? '' : 'none';
      lbCounter.textContent   = multi ? (idx + 1) + ' / ' + items.length : '';
    };

    const open = (allItems, startIdx) => {
      items = allItems;
      document.body.appendChild(overlay);
      document.body.style.overflow = 'hidden';
      show(startIdx);
      requestAnimationFrame(() => overlay.classList.add('is-open'));
    };

    const close = () => {
      overlay.classList.remove('is-open');
      setTimeout(() => { overlay.remove(); document.body.style.overflow = ''; }, 260);
    };

    overlay.addEventListener('click', e => { if (e.target === overlay) close(); });
    lbClose.addEventListener('click', close);
    lbPrev.addEventListener('click', e => { e.stopPropagation(); show(idx - 1); });
    lbNext.addEventListener('click', e => { e.stopPropagation(); show(idx + 1); });

    document.addEventListener('keydown', e => {
      if (!document.body.contains(overlay)) return;
      if (e.key === 'Escape')     close();
      if (e.key === 'ArrowLeft')  show(idx - 1);
      if (e.key === 'ArrowRight') show(idx + 1);
    });

    document.addEventListener('click', e => {
      const target = e.target.closest('.phone[data-lightbox-src], .pdf-card[data-lightbox-src]');
      if (!target) return;

      // Video phones → open video grid
      if (target.classList.contains('phone') &&
          (target.dataset.lightboxType === 'youtube' || target.dataset.lightboxType === 'video')) {
        const vPanel = target.closest('[data-panel="v"]');
        if (vPanel) { openVideoGrid(vPanel); return; }
      }

      // Image phones → open image grid
      if (target.classList.contains('phone') && target.dataset.lightboxType === 'image') {
        const iPanel = target.closest('[data-panel="i"]');
        if (iPanel) { openImageGrid(iPanel); return; }
      }

      // Website phones → open website grid
      if (target.classList.contains('phone') && target.dataset.lightboxType === 'website') {
        const wPanel = target.closest('[data-panel="w"]');
        if (wPanel) { openWebsiteGrid(wPanel); return; }
      }

      const panel    = target.closest('[data-panel]');
      const allItems = panel
        ? [...panel.querySelectorAll('.phone[data-lightbox-src], .pdf-card[data-lightbox-src]')]
        : [target];
      const start = allItems.indexOf(target);

      open(allItems, start >= 0 ? start : 0);
    });

    lbOpen = open; // expose to openVideoGrid
  })();

  /* ---- Sector filter ---- */
  let suppressHistory = false;
  const sectorBtns = [...document.querySelectorAll('.sector-pill')];
  if (sectorBtns.length) {
    sectorBtns.forEach(btn => btn.addEventListener('click', () => {
      sectorBtns.forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      const sector = btn.dataset.sector;

      // Sync URL
      if (!suppressHistory) {
        const url = new URL(window.location);
        if (sector === 'all') url.searchParams.delete('sector');
        else url.searchParams.set('sector', sector);
        history.pushState({ sector }, '', url);
      }

      document.querySelectorAll('[data-panel]').forEach(panel => {
        panel.dispatchEvent(new CustomEvent('sector:filter', { detail: { sector } }));
      });

      document.querySelectorAll('[data-agency-section]').forEach(section => {
        if (sector === 'all') {
          section.classList.remove('showcase--hidden');
        } else {
          const sectors = (section.dataset.agencySectors || '').split(',').filter(Boolean);
          section.classList.toggle('showcase--hidden', !sectors.includes(sector));
        }
      });

      if (sector !== 'all') {
        const first = document.querySelector('[data-agency-section]:not(.showcase--hidden)');
        if (first) setTimeout(() => first.scrollIntoView({ behavior: 'smooth', block: 'start' }), 60);
      }
    }));

    // Apply sector from URL on page load
    const urlSector = new URLSearchParams(window.location.search).get('sector');
    if (urlSector) {
      const btn = document.querySelector(`.sector-pill[data-sector="${CSS.escape(urlSector)}"]`);
      if (btn) { suppressHistory = true; btn.click(); suppressHistory = false; }
    }

    // Handle browser back/forward
    window.addEventListener('popstate', e => {
      const sector = (e.state && e.state.sector)
        || new URLSearchParams(window.location.search).get('sector')
        || 'all';
      const btn = document.querySelector(`.sector-pill[data-sector="${CSS.escape(sector)}"]`);
      if (btn) { suppressHistory = true; btn.click(); suppressHistory = false; }
    });
  }

  // Sector bar text search
  const sectorSearchEl = document.getElementById('sector-search');
  if (sectorSearchEl) {
    const dispatchSearch = q => {
      document.querySelectorAll('[data-panel]').forEach(p =>
        p.dispatchEvent(new CustomEvent('search:filter', { detail: { query: q } }))
      );
      document.querySelectorAll('.pdf-shelf').forEach(s =>
        s.dispatchEvent(new CustomEvent('search:filter', { detail: { query: q } }))
      );
    };
    let sbarTimer = null;
    sectorSearchEl.addEventListener('input', () => {
      const q = sectorSearchEl.value;
      dispatchSearch(q);
      clearTimeout(sbarTimer);
      sbarTimer = setTimeout(() => {
        const url = new URL(window.location);
        if (q.trim()) url.searchParams.set('q', q);
        else url.searchParams.delete('q');
        history.replaceState(history.state, '', url);
      }, 350);
    });
    const urlQ = new URLSearchParams(window.location.search).get('q');
    if (urlQ) { sectorSearchEl.value = urlQ; setTimeout(() => dispatchSearch(urlQ), 100); }
  }

  // Auto-open video grid if ?search= is present in URL
  const urlSearch = new URLSearchParams(window.location.search).get('search');
  if (urlSearch) {
    const vPanel = document.querySelector('[data-panel="v"]');
    if (vPanel) setTimeout(() => openVideoGrid(vPanel, urlSearch), 150);
  }

});
