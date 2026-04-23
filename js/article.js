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
