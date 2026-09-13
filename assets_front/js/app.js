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
});
