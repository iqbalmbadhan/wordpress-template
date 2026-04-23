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
