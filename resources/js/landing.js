/* ============================================================
   SIDESA Landing — Interactions
   ============================================================ */

(function () {
    'use strict';

    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ---------- Navbar scroll state ---------- */
    const nav = document.getElementById('siteNav');
    function updateNav() {
        if (!nav) return;
        const solid = window.scrollY > 40;
        nav.classList.toggle('nav-solid', solid);
        nav.classList.toggle('nav-transparent', !solid);
    }
    updateNav();
    window.addEventListener('scroll', updateNav, { passive: true });

    /* ---------- Mobile drawer ---------- */
    const drawer = document.getElementById('mobileDrawer');
    const openBtn = document.getElementById('openDrawer');
    const closeBtn = document.getElementById('closeDrawer');
    function openDrawer() {
        if (!drawer) return;
        drawer.classList.remove('drawer-closed');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        if (!drawer) return;
        drawer.classList.add('drawer-closed');
        document.body.style.overflow = '';
    }
    openBtn && openBtn.addEventListener('click', openDrawer);
    closeBtn && closeBtn.addEventListener('click', closeDrawer);
    drawer && drawer.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDrawer));

    /* ---------- Hero slider ---------- */
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    let current = 0;
    let heroTimer;

    function goTo(n) {
        if (!slides.length) return;
        slides.forEach((s, i) => {
            s.classList.toggle('is-active', i === n);
            s.style.opacity = i === n ? '1' : '0';
        });
        dots.forEach((d, i) => d.classList.toggle('is-active', i === n));
        current = n;
    }
    function next() { goTo((current + 1) % slides.length); }
    function restart() {
        clearInterval(heroTimer);
        if (!prefersReduced && slides.length > 1) {
            heroTimer = setInterval(next, 7000);
        }
    }
    window.heroGoTo = (n) => { goTo(n); restart(); };
    if (slides.length) { goTo(0); restart(); }

    /* ---------- Intersection reveal ---------- */
    const reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && !prefersReduced) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('is-visible');
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
        reveals.forEach(el => io.observe(el));
    } else {
        reveals.forEach(el => el.classList.add('is-visible'));
    }

    /* ---------- Stat counter ---------- */
    const counters = document.querySelectorAll('[data-counter]');
    if (counters.length && 'IntersectionObserver' in window) {
        const cio = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (!e.isIntersecting) return;
                const el = e.target;
                const target = parseInt(el.dataset.counter, 10) || 0;
                if (prefersReduced) { el.textContent = target.toLocaleString('id-ID'); cio.unobserve(el); return; }
                const dur = 1400;
                const start = performance.now();
                function step(now) {
                    const p = Math.min((now - start) / dur, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.round(target * eased).toLocaleString('id-ID');
                    if (p < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
                cio.unobserve(el);
            });
        }, { threshold: 0.4 });
        counters.forEach(el => cio.observe(el));
    }

    /* ---------- Active nav link (scroll spy) ---------- */
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('#siteNav .nav-link[href^="#"]');
    if (sections.length && navLinks.length && 'IntersectionObserver' in window) {
        const sio = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    const id = e.target.id;
                    navLinks.forEach(l => l.classList.toggle('is-active', l.getAttribute('href') === '#' + id));
                }
            });
        }, { threshold: 0.35 });
        sections.forEach(s => sio.observe(s));
    }
})();
