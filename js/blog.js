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
