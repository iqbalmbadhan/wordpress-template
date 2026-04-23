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
