// ===== HAMBURGER MENU =====
function closeDrawer() {
  var h = document.getElementById('navHamburger');
  var d = document.getElementById('mobileDrawer');
  if (h) { h.classList.remove('open'); h.setAttribute('aria-expanded', 'false'); }
  if (d) { d.classList.remove('open'); d.setAttribute('aria-hidden', 'true'); }
}

(function () {
  var h = document.getElementById('navHamburger');
  var d = document.getElementById('mobileDrawer');
  if (!h || !d) return;

  h.addEventListener('click', function () {
    var isOpen = d.classList.toggle('open');
    h.classList.toggle('open', isOpen);
    h.setAttribute('aria-expanded', String(isOpen));
    d.setAttribute('aria-hidden', String(!isOpen));
  });

  document.addEventListener('click', function (e) {
    var nav = document.getElementById('navbar');
    if (nav && !nav.contains(e.target) && !d.contains(e.target)) closeDrawer();
  });
}());

// ===== FADE IN OBSERVER =====
(function () {
  var els = document.querySelectorAll('.fade-in');
  var obs = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) e.target.classList.add('visible');
    });
  }, { threshold: 0.1 });
  els.forEach(function (el) { obs.observe(el); });
}());
// ===== TWEAKS PANEL =====
function setAccent(name, btn) {
  var map = {
    teal:   ['oklch(72% 0.155 195)', 'oklch(72% 0.155 145)'],
    violet: ['oklch(68% 0.18 290)',  'oklch(68% 0.18 260)'],
    gold:   ['oklch(78% 0.15 85)',   'oklch(78% 0.15 60)'],
    coral:  ['oklch(68% 0.18 30)',   'oklch(68% 0.18 10)']
  };
  document.documentElement.style.setProperty('--accent',  map[name][0]);
  document.documentElement.style.setProperty('--accent2', map[name][1]);
  btn.closest('.tweak-row').querySelectorAll('.tweak-btn').forEach(function (b) { b.classList.remove('active'); });
  btn.classList.add('active');
  window.parent.postMessage({ type: '__edit_mode_set_keys', edits: { accent: name } }, '*');
}

function setBg(name, btn) {
  var map = {
    dark:     ['oklch(11% 0.01 260)',  'oklch(14% 0.012 260)', 'oklch(17% 0.012 260)'],
    midnight: ['oklch(8% 0.025 265)',  'oklch(11% 0.03 265)',  'oklch(14% 0.03 265)'],
    warm:     ['oklch(10% 0.015 40)',  'oklch(13% 0.018 40)',  'oklch(16% 0.018 40)']
  };
  document.documentElement.style.setProperty('--bg',  map[name][0]);
  document.documentElement.style.setProperty('--bg2', map[name][1]);
  document.documentElement.style.setProperty('--bg3', map[name][2]);
  btn.closest('.tweak-row').querySelectorAll('.tweak-btn').forEach(function (b) { b.classList.remove('active'); });
  btn.classList.add('active');
  window.parent.postMessage({ type: '__edit_mode_set_keys', edits: { bg: name } }, '*');
}

function setFont(name, btn) {
  var map = {
    playfair:  ["'Playfair Display', Georgia, serif", "'DM Sans', Helvetica, sans-serif"],
    geo:       ["'DM Sans', Helvetica, sans-serif",   "'DM Sans', Helvetica, sans-serif"],
    editorial: ["'Playfair Display', Georgia, serif", "'DM Sans', Helvetica, sans-serif"]
  };
  document.documentElement.style.setProperty('--ff-display', map[name][0]);
  document.documentElement.style.setProperty('--ff-body',    map[name][1]);
  btn.closest('.tweak-row').querySelectorAll('.tweak-btn').forEach(function (b) { b.classList.remove('active'); });
  btn.classList.add('active');
}

window.addEventListener('message', function (e) {
  var panel = document.getElementById('tweaks-panel');
  if (!panel) return;
  if (e.data && e.data.type === '__activate_edit_mode')   panel.style.display = 'block';
  if (e.data && e.data.type === '__deactivate_edit_mode') panel.style.display = 'none';
});
window.parent.postMessage({ type: '__edit_mode_available' }, '*');
// ===== NAV SCROLL & ACTIVE LINK =====
(function () {
  var navbar = document.getElementById('navbar');
  var navLinks = document.querySelectorAll('.nav-links a');
  var sections = document.querySelectorAll('section[id]');
  if (!navbar) return;

  window.addEventListener('scroll', function () {
    navbar.classList.toggle('scrolled', window.scrollY > 60);
    var current = '';
    sections.forEach(function (s) {
      if (window.scrollY >= s.offsetTop - 100) current = s.id;
    });
    navLinks.forEach(function (a) {
      a.classList.toggle('active', a.getAttribute('href') === '#' + current);
    });
  });
}());

// ===== COUNTER ANIMATION =====
(function () {
  function animateCounter(el, target, duration) {
    duration = duration || 1200;
    var start = 0;
    var step = function (timestamp) {
      if (!start) start = timestamp;
      var progress = Math.min((timestamp - start) / duration, 1);
      el.textContent = Math.floor(progress * target);
      if (progress < 1) requestAnimationFrame(step);
      else el.textContent = target;
    };
    requestAnimationFrame(step);
  }

  var counterEls = document.querySelectorAll('.counter');
  var counterObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        animateCounter(e.target, parseInt(e.target.dataset.target));
        counterObserver.unobserve(e.target);
      }
    });
  }, { threshold: 0.5 });
  counterEls.forEach(function (el) { counterObserver.observe(el); });
}());

// ===== SKILL BARS =====
(function () {
  var skillFills = document.querySelectorAll('.skill-fill');
  var skillObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        e.target.style.width = e.target.dataset.width + '%';
        skillObserver.unobserve(e.target);
      }
    });
  }, { threshold: 0.3 });
  skillFills.forEach(function (el) { skillObserver.observe(el); });
}());

// ===== CONTACT FORM =====
function handleSubmit(e) {
  e.preventDefault();
  var success = document.getElementById('formSuccess');
  if (success) success.style.display = 'block';
  var btn = e.target.querySelector('.form-submit');
  if (btn) { btn.textContent = 'Sent ✓'; btn.style.opacity = '0.6'; }
  setTimeout(function () { if (success) success.style.display = 'none'; }, 5000);
}
// ===== FILTER POSTS =====
function filterPosts(cat, btn) {
  document.querySelectorAll('.filter-pill').forEach(function (p) { p.classList.remove('active'); });
  if (btn) btn.classList.add('active');
  document.querySelectorAll('.post-card').forEach(function (card) {
    var cats = card.dataset.cat || '';
    var show = cat === 'all' || cats.includes(cat);
    card.style.display = show ? '' : 'none';
    card.style.opacity  = show ? '1' : '0';
  });
}

// ===== BLOG LAYOUT TWEAK =====
function setLayout(type, btn) {
  var layout  = document.querySelector('.blog-layout');
  var sidebar = document.querySelector('.sidebar');
  if (layout)  layout.style.gridTemplateColumns = type === 'full' ? '1fr' : '1fr 300px';
  if (sidebar) sidebar.style.display = type === 'full' ? 'none' : '';
  btn.closest('.tweak-row').querySelectorAll('.tweak-btn').forEach(function (b) { b.classList.remove('active'); });
  btn.classList.add('active');
}
// ===== TABLE OF CONTENTS — ACTIVE ON SCROLL =====
(function () {
  var headings = document.querySelectorAll('.article-body h2[id], .article-body h3[id]');
  var tocLinks = document.querySelectorAll('.toc-item a');
  if (!headings.length || !tocLinks.length) return;

  var tocObserver = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) {
      if (e.isIntersecting) {
        tocLinks.forEach(function (a) { a.classList.remove('toc-active'); });
        var active = document.querySelector('.toc-item a[href="#' + e.target.id + '"]');
        if (active) active.classList.add('toc-active');
      }
    });
  }, { rootMargin: '-20% 0% -70% 0%' });

  headings.forEach(function (h) { tocObserver.observe(h); });

  // Smooth scroll for TOC links
  tocLinks.forEach(function (a) {
    a.addEventListener('click', function (e) {
      e.preventDefault();
      var target = document.querySelector(a.getAttribute('href'));
      if (target) window.scrollTo({ top: target.offsetTop - 90, behavior: 'smooth' });
    });
  });
}());

// ===== ARTICLE-SPECIFIC TWEAKS =====
function setFontSize(size, btn) {
  var body = document.querySelector('.article-body');
  if (body) {
    body.querySelectorAll('p, li').forEach(function (el) { el.style.fontSize = size + 'px'; });
  }
  btn.closest('.tweak-row').querySelectorAll('.tweak-btn').forEach(function (b) { b.classList.remove('active'); });
  btn.classList.add('active');
  window.parent.postMessage({ type: '__edit_mode_set_keys', edits: { fontSize: size } }, '*');
}

function setWidth(w, btn) {
  document.querySelectorAll('.article-layout').forEach(function (el) { el.style.maxWidth = w; });
  btn.closest('.tweak-row').querySelectorAll('.tweak-btn').forEach(function (b) { b.classList.remove('active'); });
  btn.classList.add('active');
  window.parent.postMessage({ type: '__edit_mode_set_keys', edits: { width: w } }, '*');
}
