/* Dawn Simmons — Customizer live preview (postMessage) */
(function ($) {
    'use strict';

    var accentMap = {
        teal:   { accent: 'oklch(72% 0.155 195)', accent2: 'oklch(72% 0.155 145)' },
        violet: { accent: 'oklch(68% 0.18 290)',  accent2: 'oklch(68% 0.18 260)'  },
        gold:   { accent: 'oklch(78% 0.15 85)',   accent2: 'oklch(78% 0.15 60)'   },
        coral:  { accent: 'oklch(68% 0.18 30)',   accent2: 'oklch(68% 0.18 10)'   }
    };

    var bgMap = {
        dark:     { bg: 'oklch(11% 0.01 260)',  bg2: 'oklch(14% 0.012 260)', bg3: 'oklch(17% 0.012 260)' },
        midnight: { bg: 'oklch(8% 0.025 265)',  bg2: 'oklch(11% 0.03 265)',  bg3: 'oklch(14% 0.03 265)'  },
        warm:     { bg: 'oklch(10% 0.015 40)',  bg2: 'oklch(13% 0.018 40)',  bg3: 'oklch(16% 0.018 40)'  }
    };

    var fontMap = {
        playfair:  { display: "'Playfair Display', Georgia, serif",  body: "'DM Sans', Helvetica, sans-serif" },
        geo:       { display: "'DM Sans', Helvetica, sans-serif",    body: "'DM Sans', Helvetica, sans-serif" },
        editorial: { display: "'Playfair Display', Georgia, serif",  body: "'DM Sans', Helvetica, sans-serif" }
    };

    function setCSSVar(name, value) {
        document.documentElement.style.setProperty(name, value);
    }

    /* Accent color */
    wp.customize('ds_accent_color', function (value) {
        value.bind(function (v) {
            var map = accentMap[v] || accentMap.teal;
            setCSSVar('--accent',  map.accent);
            setCSSVar('--accent2', map.accent2);
        });
    });

    /* Background theme */
    wp.customize('ds_bg_theme', function (value) {
        value.bind(function (v) {
            var map = bgMap[v] || bgMap.dark;
            setCSSVar('--bg',  map.bg);
            setCSSVar('--bg2', map.bg2);
            setCSSVar('--bg3', map.bg3);
        });
    });

    /* Font pairing */
    wp.customize('ds_font_pair', function (value) {
        value.bind(function (v) {
            var map = fontMap[v] || fontMap.playfair;
            setCSSVar('--ff-display', map.display);
            setCSSVar('--ff-body',    map.body);
        });
    });

    /* Logo text */
    wp.customize('ds_logo_text', function (value) {
        value.bind(function (v) {
            document.querySelectorAll('.nav-logo, .footer-logo').forEach(function (el) {
                el.textContent = v;
            });
        });
    });

    /* Nav CTA text */
    wp.customize('ds_cta_text', function (value) {
        value.bind(function (v) {
            var cta = document.querySelector('.nav-cta');
            if (cta) cta.textContent = v;
        });
    });

    /* Footer copyright */
    wp.customize('ds_footer_copy', function (value) {
        value.bind(function (v) {
            var copy = document.querySelector('.footer-copy');
            if (copy) copy.innerHTML = v;
        });
    });

}(jQuery));
