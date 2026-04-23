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
