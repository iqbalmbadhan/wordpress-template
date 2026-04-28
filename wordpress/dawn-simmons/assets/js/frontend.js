/* Dawn Simmons Theme — Frontend JS */
(function () {
    'use strict';

    /* ── Hamburger nav ── */
    function initNav() {
        var h = document.getElementById('navHamburger');
        var d = document.getElementById('mobileDrawer');
        var navbar = document.getElementById('navbar');
        if (!h || !d) return;

        h.addEventListener('click', function () {
            var isOpen = d.classList.toggle('open');
            h.classList.toggle('open', isOpen);
            h.setAttribute('aria-expanded', String(isOpen));
            d.setAttribute('aria-hidden', String(!isOpen));
        });

        document.addEventListener('click', function (e) {
            if (navbar && !navbar.contains(e.target) && !d.contains(e.target)) {
                h.classList.remove('open');
                d.classList.remove('open');
                h.setAttribute('aria-expanded', 'false');
                d.setAttribute('aria-hidden', 'true');
            }
        });
    }

    /* ── Scroll: sticky nav + active link ── */
    function initScroll() {
        var navbar = document.getElementById('navbar');
        var sections = document.querySelectorAll('section[id]');
        if (!navbar) return;

        window.addEventListener('scroll', function () {
            navbar.classList.toggle('scrolled', window.scrollY > 60);
            if (!sections.length) return;
            var navLinks = document.querySelectorAll('.nav-links a');
            var current = '';
            sections.forEach(function (s) {
                if (window.scrollY >= s.offsetTop - 100) current = s.id;
            });
            navLinks.forEach(function (a) {
                a.classList.toggle('active', a.getAttribute('href') === '#' + current);
            });
        }, { passive: true });
    }

    /* ── Fade-in on scroll ── */
    function initFadeIn() {
        var els = document.querySelectorAll('.fade-in');
        if (!els.length) return;
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        els.forEach(function (el) { obs.observe(el); });
    }

    /* ── Counter animation ── */
    function animateCounter(el, target) {
        var start = null;
        var duration = 1200;
        function step(ts) {
            if (!start) start = ts;
            var progress = Math.min((ts - start) / duration, 1);
            el.textContent = Math.floor(progress * target);
            if (progress < 1) requestAnimationFrame(step);
            else el.textContent = target;
        }
        requestAnimationFrame(step);
    }

    function initCounters() {
        var counterEls = document.querySelectorAll('.counter');
        if (!counterEls.length) return;
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    animateCounter(e.target, parseInt(e.target.dataset.target, 10));
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.5 });
        counterEls.forEach(function (el) { obs.observe(el); });
    }

    /* ── Skill bar animation ── */
    function initSkillBars() {
        var fills = document.querySelectorAll('.skill-fill');
        if (!fills.length) return;
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    e.target.style.width = (e.target.dataset.width || '0') + '%';
                    obs.unobserve(e.target);
                }
            });
        }, { threshold: 0.3 });
        fills.forEach(function (el) { obs.observe(el); });
    }

    /* ── Table of contents scroll spy ── */
    function initTOC() {
        var headings = document.querySelectorAll('.article-body h2[id], .article-body h3[id]');
        var tocLinks = document.querySelectorAll('.toc-item a');
        if (!headings.length || !tocLinks.length) return;

        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (e) {
                if (e.isIntersecting) {
                    tocLinks.forEach(function (a) { a.classList.remove('toc-active'); });
                    var active = document.querySelector('.toc-item a[href="#' + e.target.id + '"]');
                    if (active) active.classList.add('toc-active');
                }
            });
        }, { rootMargin: '-20% 0% -70% 0%' });

        headings.forEach(function (h) { obs.observe(h); });

        tocLinks.forEach(function (a) {
            a.addEventListener('click', function (e) {
                e.preventDefault();
                var target = document.querySelector(a.getAttribute('href'));
                if (target) window.scrollTo({ top: target.offsetTop - 90, behavior: 'smooth' });
            });
        });
    }

    /* ── Contact form (native fallback, real AJAX submission) ── */
    function initContactForm() {
        var form = document.querySelector('.contact-form:not(.wpcf7-form)');
        if (!form) return;
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            var btn     = form.querySelector('.form-submit');
            var success = document.getElementById('formSuccess');
            var origTxt = btn ? btn.textContent : '';

            if (btn) { btn.disabled = true; btn.textContent = 'Sending…'; }

            fetch(form.action || window.location.href, {
                method:  'POST',
                body:    new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (json) {
                if (!json.success) throw new Error(json.data || 'error');
                if (success) success.style.display = 'block';
                if (btn) { btn.textContent = 'Sent ✓'; btn.style.opacity = '0.6'; }
                form.reset();
                setTimeout(function () {
                    if (success) success.style.display = 'none';
                    if (btn) { btn.disabled = false; btn.textContent = origTxt; btn.style.opacity = ''; }
                }, 6000);
            })
            .catch(function () {
                if (btn) { btn.disabled = false; btn.textContent = origTxt; }
                if (success) {
                    success.textContent = 'Could not send — please email directly.';
                    success.style.display = 'block';
                    setTimeout(function () { success.style.display = 'none'; }, 5000);
                }
            });
        });
    }

    /* ── Re-init all dynamic observers after content swap ── */
    function reinit() {
        initFadeIn();
        initCounters();
        initSkillBars();
        initTOC();
        initContactForm();
    }

    /* ── AJAX navigation (no full-page reload) ── */
    var ajaxNav = (function () {
        var bar = null;
        var barTimer = null;
        var origin = window.location.origin;
        var loading = false;

        function createBar() {
            if (bar) return;
            bar = document.createElement('div');
            bar.id = 'ds-progress-bar';
            bar.style.cssText = [
                'position:fixed', 'top:0', 'left:0', 'height:3px',
                'background:var(--accent,#00c9a7)', 'transition:width .25s ease,opacity .3s ease',
                'z-index:99999', 'width:0', 'opacity:0', 'pointer-events:none'
            ].join(';');
            document.body.appendChild(bar);
        }

        function progressStart() {
            createBar();
            clearTimeout(barTimer);
            bar.style.opacity = '1';
            bar.style.width = '0';
            requestAnimationFrame(function () { bar.style.width = '70%'; });
        }

        function progressDone() {
            bar.style.width = '100%';
            barTimer = setTimeout(function () {
                bar.style.opacity = '0';
                setTimeout(function () { bar.style.width = '0'; }, 300);
            }, 250);
        }

        function isInternal(href) {
            try {
                var u = new URL(href, origin);
                return u.origin === origin;
            } catch (e) { return false; }
        }

        function isSamePageAnchor(href) {
            try {
                var u = new URL(href, window.location.href);
                return u.pathname === window.location.pathname && u.hash !== '';
            } catch (e) { return false; }
        }

        function swapContent(html, url) {
            var parser = new DOMParser();
            var doc = parser.parseFromString(html, 'text/html');

            /* swap <main> — fall back to full navigation if target page has no main */
            var newMain = doc.querySelector('#main-content, main');
            var curMain = document.querySelector('#main-content, main');
            if (!newMain || !curMain) {
                window.location.href = url;
                return;
            }
            curMain.style.opacity = '0';
            curMain.style.transition = 'opacity .15s';
            setTimeout(function () {
                curMain.innerHTML = newMain.innerHTML;
                curMain.style.opacity = '1';
                window.scrollTo(0, 0);
                reinit();
            }, 150);

            /* swap <title> */
            var newTitle = doc.querySelector('title');
            if (newTitle) document.title = newTitle.textContent;

            /* update active nav link */
            document.querySelectorAll('.nav-links a, .mobile-nav a').forEach(function (a) {
                try {
                    a.classList.toggle('active', new URL(a.href).pathname === new URL(url).pathname);
                } catch (e) {}
            });

            /* close mobile drawer */
            var drawer = document.getElementById('mobileDrawer');
            var burger = document.getElementById('navHamburger');
            if (drawer) { drawer.classList.remove('open'); drawer.setAttribute('aria-hidden', 'true'); }
            if (burger) { burger.classList.remove('open'); burger.setAttribute('aria-expanded', 'false'); }
        }

        function navigate(url) {
            if (loading) return;
            loading = true;
            progressStart();

            fetch(url, { headers: { 'X-DS-Ajax-Nav': '1' } })
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    history.pushState({ dsAjax: true }, '', url);
                    swapContent(html, url);
                    progressDone();
                })
                .catch(function () {
                    window.location.href = url;
                })
                .finally(function () { loading = false; });
        }

        function init() {
            /* skip AJAX nav inside the Customizer preview iframe */
            if (window.location.search.indexOf('wp-customize=on') !== -1) return;

            /* intercept link clicks */
            document.addEventListener('click', function (e) {
                var a = e.target.closest('a[href]');
                if (!a) return;
                /* never intercept admin bar links (Customize, Edit, etc.) */
                if (a.closest('#wpadminbar')) return;
                var href = a.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript')) return;
                if (isSamePageAnchor(href)) return;
                if (!isInternal(href)) return;
                if (a.target && a.target !== '_self') return;
                if (a.hasAttribute('download')) return;
                /* skip admin / login / wp-content links */
                var full = new URL(href, origin).pathname;
                if (/\/(wp-admin|wp-login\.php|wp-cron\.php)/.test(full)) return;
                e.preventDefault();
                navigate(new URL(href, origin).href);
            });

            /* handle back/forward — ignore hash-only changes (anchor nav within same page) */
            window.addEventListener('popstate', function (e) {
                if (!e.state || !e.state.dsAjax) return;
                navigate(window.location.href);
            });
        }

        return { init: init };
    }());

    /* ── Boot ── */
    document.addEventListener('DOMContentLoaded', function () {
        initNav();
        initScroll();
        initFadeIn();
        initCounters();
        initSkillBars();
        initTOC();
        initContactForm();
        ajaxNav.init();
    });
}());
