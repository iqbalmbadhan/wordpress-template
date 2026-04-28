# Dawn Simmons — WordPress Portfolio Theme

A professional, dark-mode WordPress theme for ServiceNow consultants and AI transformation experts. Editable via the **Block Editor (Gutenberg)** with live Customizer controls, a 3-step setup wizard, and WooCommerce support.

---

## Repository Structure

```
wordpress-template/
├── wordpress/
│   └── dawn-simmons/           ← Install this as your WordPress theme
│       ├── style.css               Theme header & identity
│       ├── theme.json              Block editor design tokens
│       ├── functions.php           Core bootstrap
│       ├── header.php / footer.php
│       ├── front-page.php          Homepage template
│       ├── page.php                Default page template
│       ├── page-fullwidth.php      Full-width template (for Services)
│       ├── single.php              Single post template
│       ├── archive.php             Blog archive template
│       ├── 404.php                 404 template
│       ├── assets/
│       │   ├── css/
│       │   │   ├── main.css            Full theme stylesheet
│       │   │   ├── editor.css          Block editor styles
│       │   │   └── woocommerce.css     WooCommerce overrides
│       │   └── js/
│       │       ├── frontend.js         Nav, animations, AJAX nav, contact form
│       │       ├── customizer-preview.js  Live Customizer postMessage handler
│       │       └── blocks/             ← Output of `npm run build` (see below)
│       ├── inc/
│       │   ├── class-plugin-checker.php    Plugin notice system
│       │   ├── class-setup-wizard.php      First-run wizard (3 steps)
│       │   ├── class-demo-importer.php     Demo content creator
│       │   ├── enqueue.php                 Asset enqueuing
│       │   ├── customizer.php              Theme Customizer options
│       │   ├── template-functions.php      Helper functions + CSS variable output
│       │   └── blocks/                     Gutenberg block registration + PHP renderers
│       │       ├── register-blocks.php
│       │       ├── hero/block.json
│       │       ├── services/block.json
│       │       ├── ai-section/block.json
│       │       ├── about/block.json
│       │       ├── testimonials/block.json
│       │       └── contact/block.json
│       └── woocommerce/
│           ├── archive-product.php     Shop page template
│           └── single-product.php      Product page template
│
├── blocks/                     ← React source for Gutenberg block editor UI
│   ├── package.json
│   └── src/
│       ├── index.js
│       └── blocks/
│           ├── hero/index.js
│           ├── services/index.js
│           ├── ai-section/index.js
│           ├── about/index.js
│           ├── testimonials/index.js
│           └── contact/index.js
│
└── src/                        ← React + Vite standalone demo app
```

---

## Requirements

| Requirement | Version |
|---|---|
| WordPress | 6.4 or higher |
| PHP | 8.0 or higher |
| MySQL | 5.7 / MariaDB 10.4 or higher |
| Node.js *(for block editor UI)* | 18 or higher |
| npm *(for block editor UI)* | 9 or higher |

---

## Installation

### Step 1 — Install the Theme

Copy the `wordpress/dawn-simmons/` folder into your WordPress installation:

```
wp-content/themes/dawn-simmons/
```

Go to **WordPress Admin → Appearance → Themes** and activate **Dawn Simmons Portfolio**.

---

### Step 2 — First-Run Setup Wizard

After activation, WordPress redirects automatically to the **Setup Wizard**:

```
WP Admin → Dashboard → Theme Setup
```

The wizard has three steps:

#### Step 1 — Install Plugins

| Plugin | Type | Purpose |
|---|---|---|
| WooCommerce | Recommended | Shop, cart, checkout, product pages |
| Contact Form 7 | Recommended | Contact section form handling |

Click **Install** or **Activate** next to each plugin from within the wizard. Both are optional — the theme works without them.

#### Step 2 — Import Demo Content

Creates everything in one click:

- Pages: Home, Blog, About, Services, Contact
- Primary and footer navigation menus
- 3 sample blog posts with categories and tags
- WooCommerce shop pages (if WooCommerce is active)
- Full homepage content using Gutenberg blocks

#### Step 3 — Done

Links to your live site and back to the WordPress dashboard.

---

### Step 3 — Build Block Editor Controls (Optional)

The theme renders entirely via **PHP** — no build step is needed to see your site. Pages display correctly from the moment you activate the theme.

The build step enables **sidebar inspector panels** in the Block Editor — the text and image controls visible in the right-hand panel when you select a block on the homepage.

```bash
cd blocks
npm install
npm run build
```

Output is written to `wordpress/dawn-simmons/assets/js/blocks/index.js`. The theme loads it automatically when the file is present.

For development (watch mode):

```bash
npm run start
```

---

## Customization

### WordPress Customizer

**Appearance → Customize** exposes real-time controls:

| Control | Options |
|---|---|
| Accent Color | Teal (default) · Violet · Gold · Coral |
| Background Theme | Dark (default) · Midnight · Warm |
| Font Pair | Playfair Display + DM Sans · Geometric (DM Sans only) · Editorial |
| Logo Text | Text in the navbar logo area |
| CTA Button Text | Navbar call-to-action label |
| CTA Button URL | Link target for the navbar CTA |
| Footer Copyright | Footer copy line |

Changes apply instantly via CSS custom properties. Click **Save & Publish** to persist them.

> **Note:** The Customizer preview pane disables AJAX navigation to avoid interfering with live-preview postMessage communication.

---

### Block Editor

Each homepage block has a full **Settings panel** in the right sidebar:

| Block | Editable Fields |
|---|---|
| **Hero** | Eyebrow, heading, subheading, role bullets, stats (number/suffix/label), CTA buttons, profile photo |
| **AI Section** | Headline, lead text, capability pills, flow steps, feature cards |
| **Services** | Eyebrow, title, up to 6 service cards (number, title, description, tags) |
| **About** | Bio paragraphs, profile photo, skill bars (name + %), details grid |
| **Testimonials** | Eyebrow, title, testimonial cards (quote, name, role) |
| **Contact** | Eyebrow, title, subtitle, email, location, response time, CF7 form ID |

---

## Pages & Templates

| Template File | Used For |
|---|---|
| `front-page.php` | Static homepage (Gutenberg blocks) |
| `page.php` | Standard pages (About, Contact) |
| `page-fullwidth.php` | Full-width pages with no header/sidebar — assign to the Services page |
| `single.php` | Blog posts — breadcrumb, reading time, TOC, author box, share, related posts |
| `archive.php` | Blog archive — featured post + post grid with sidebar and category filter |
| `404.php` | Custom 404 with links to Home and Blog |
| `woocommerce/archive-product.php` | Shop / product grid |
| `woocommerce/single-product.php` | Single product page |

### Assigning the Full-Width Template to the Services Page

1. Go to **WordPress Admin → Pages → Services → Edit**
2. In the right-hand **Page** panel, find **Template**
3. Select **Full Width Page**
4. Click **Update**

The Services page will render edge-to-edge with no page-hero header or padding container.

---

## Blog — Pinning a Featured Post

The blog archive always shows the most recent post in the **featured slot** at the top. To pin a specific post there permanently:

1. Open the post in the WordPress Editor
2. In the right-hand **Post** panel, scroll down to **Discussion** or look under the panel menu for **Stick to the top of the blog**
3. Check that option and click **Update**

The post will appear in the featured slot with a **★ Pinned** badge regardless of its publish date. The regular posts grid below continues to show the remaining posts in date order.

To unpin it, uncheck the same option.

---

## WooCommerce

Full WooCommerce support out of the box:

- Product gallery zoom, lightbox, and slider
- Dark-mode product grid (3 → 2 → 1 column at breakpoints)
- Styled cart table, checkout form, order notices, and breadcrumbs
- All WooCommerce pages created automatically by the setup wizard

The WooCommerce stylesheet loads only when WooCommerce is active — no extra CSS on non-shop installs.

---

## Navigation & Menus

Two menu locations:

| Location | Handle | Typical items |
|---|---|---|
| Primary Navigation | `primary` | Home, Services, About, Blog, Shop, Contact |
| Footer Navigation | `footer` | Blog, About, Contact |

Assign menus at **Appearance → Menus**. The demo importer sets them automatically.

---

## Widget Areas

| Sidebar | Used in |
|---|---|
| `sidebar-blog` | Blog archive sidebar, single post sidebar |
| `footer-1` | Footer column 1 |
| `footer-2` | Footer column 2 |
| `footer-3` | Footer column 3 |

Add widgets at **Appearance → Widgets**.

---

## Frontend JavaScript

`assets/js/frontend.js` — zero runtime dependencies, vanilla JS:

| Feature | How it works |
|---|---|
| Mobile nav drawer | Hamburger toggle with `aria-expanded` |
| Sticky navbar | `scrolled` class applied after 60 px of scroll |
| Active nav link | Scroll-spy on `section[id]` elements |
| Fade-in on scroll | `IntersectionObserver` on `.fade-in` elements |
| Counter animation | `IntersectionObserver` on `.counter[data-target]` |
| Skill bar fill | `IntersectionObserver` on `.skill-fill[data-width]` |
| Table of contents | Scroll-spy + smooth scroll for `.toc-item a` |
| Contact form fallback | Native form submit handler (used when CF7 is not active) |
| AJAX page navigation | `fetch()` + `history.pushState()` for smooth page transitions |

### AJAX Navigation Notes

- Admin bar links (Customize, Edit, etc.) are excluded — they always do a full load.
- Hash anchor links (`#contact`, `#services`) are excluded — they scroll smoothly without any page fetch.
- The Customizer preview pane is excluded to preserve postMessage communication.
- Browser back/forward only triggers AJAX navigation for states pushed by this system — hash changes never cause a page reload.

---

## Design Tokens (CSS Variables)

All component styles reference these variables. The Customizer updates them at runtime:

```css
:root {
  --bg:          oklch(11% 0.01 260);    /* Page background */
  --bg2:         oklch(14% 0.012 260);   /* Alternate section bg */
  --bg3:         oklch(17% 0.012 260);   /* Card backgrounds */
  --surface:     oklch(19% 0.015 260);   /* Elevated surfaces */
  --border:      oklch(25% 0.015 260);   /* Borders and dividers */
  --muted:       oklch(45% 0.01 260);    /* Muted / placeholder text */
  --text:        oklch(92% 0.005 260);   /* Primary text */
  --text2:       oklch(65% 0.008 260);   /* Secondary text */
  --accent:      oklch(72% 0.155 195);   /* Brand color (teal default) */
  --accent2:     oklch(72% 0.155 145);   /* Secondary accent */
  --ff-display:  'Playfair Display', Georgia, serif;
  --ff-body:     'DM Sans', Helvetica, sans-serif;
}
```

---

## React — Gutenberg Block Editor UI (`blocks/`)

A **WordPress-native React build** using `@wordpress/scripts` that adds sidebar inspector panels to the six custom blocks. The frontend always renders via PHP — this build only affects the editing experience inside the WordPress Block Editor.

### When you need it

- You want to edit homepage content (text, images, stats, testimonials) through the Block Editor sidebar.
- You see blocks on the homepage but no controls appear in the right-hand panel when you select one.

### Setup

```bash
cd blocks
npm install
npm run build
```

Output: `wordpress/dawn-simmons/assets/js/blocks/index.js` — loaded automatically by the theme.

### Development

```bash
cd blocks
npm run start    # watch mode
npm run lint:js  # lint
```

### How blocks are structured

Each block at `blocks/src/blocks/{name}/index.js` calls `registerBlockType()` with:

- **`metadata`** — imported from the corresponding `block.json`
- **`edit()`** — React component with `InspectorControls` sidebar UI
- **`save()`** — returns `null`; PHP renders all frontend HTML

```js
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import metadata from '../../../wordpress/dawn-simmons/inc/blocks/hero/block.json';

registerBlockType(metadata.name, {
    ...metadata,

    edit({ attributes, setAttributes }) {
        return (
            <>
                <InspectorControls>
                    <PanelBody title="Hero Content">
                        <TextControl
                            label="Eyebrow Text"
                            value={attributes.eyebrow}
                            onChange={v => setAttributes({ eyebrow: v })}
                        />
                    </PanelBody>
                </InspectorControls>
            </>
        );
    },

    save: () => null,
});
```

### Adding a new editable field

1. Open `blocks/src/blocks/{name}/index.js`
2. Add a control inside `<InspectorControls>` and wire it to `setAttributes()`
3. Add the attribute to `inc/blocks/{name}/block.json`
4. Read `$attrs['your_attr']` in the PHP render callback in `inc/blocks/register-blocks.php`
5. Run `npm run build`

---

## React — Standalone Demo App (`src/`)

A **self-contained React + Vite application** that reproduces the full portfolio site with no WordPress dependency. Useful for:

- Live-previewing the design before importing to WordPress
- Sharing a static demo with clients
- Deploying a static portfolio to Netlify / Vercel / GitHub Pages

### Tech stack

| Package | Purpose |
|---|---|
| `react` + `react-dom` 18 | UI rendering |
| `react-router-dom` 6 | Client-side routing (HashRouter) |
| `vite` 6 | Dev server and bundler |
| `@vitejs/plugin-react` | JSX transform + Fast Refresh |

### Commands

```bash
# from repo root
npm install
npm run dev      # http://localhost:5173
npm run build    # output → dist/
npm run preview  # serve dist/ locally
```

### Routes

| URL | Component |
|---|---|
| `/#/` | `src/pages/HomePage.jsx` — all 6 sections |
| `/#/blog` | `src/pages/BlogPage.jsx` — post grid |
| `/#/article` | `src/pages/ArticlePage.jsx` — single post |

### Project layout

```
src/
├── main.jsx               Entry point
├── App.jsx                Router + ThemeProvider
├── context/
│   └── ThemeContext.jsx   Global accent / bg / font state
├── pages/
│   ├── HomePage.jsx
│   ├── BlogPage.jsx
│   └── ArticlePage.jsx
└── components/
    ├── Navbar.jsx
    ├── Footer.jsx
    └── EditorPanel.jsx    Live theme switcher (accent / bg / font)
```

### Deploying

```bash
npm run build
```

Deploy the `dist/` folder to any static host. HashRouter means all routes work without server-side redirect rules.

---

## Custom REST API Endpoint

```
GET /wp-json/dawn-simmons/v1/settings
```

Returns the active theme configuration:

```json
{
  "accent":      "teal",
  "bg":          "dark",
  "font":        "playfair",
  "editor_pref": "gutenberg"
}
```

---

## WP-CLI Reference

```bash
# Re-run demo content import
wp eval 'DS_Demo_Importer::run();'

# Check current editor preference
wp option get ds_editor_preference

# Reset setup wizard (redirects on next admin visit)
wp option delete ds_setup_complete

# Flush all caches
wp cache flush
wp rewrite flush
```

---

## Troubleshooting

**Homepage looks blank after import**
Go to **Settings → Reading** and confirm *A static page* is selected, with **Home** as Homepage and **Blog** as Posts page.

**Block editor shows no sidebar controls**
Run `cd blocks && npm install && npm run build`. The build only enables editor UI — the frontend renders via PHP regardless.

**Customizer changes not saving**
Open the Customizer, make your changes, and click **Save & Publish** in the top-left panel. If the button is greyed out, a JavaScript error in the preview pane may be blocking it — check the browser console.

**Contact form doesn't send email**
Install **Contact Form 7**, create a form, copy its numeric ID, and enter it in the Contact block's CF7 ID field. Without CF7, the built-in native form is used (requires PHP `mail()` to work on your host).

**WooCommerce pages are unstyled**
Verify WooCommerce is active. The `ds-woocommerce` stylesheet loads conditionally on WooCommerce pages only.

**"Start a Conversation" / "View My Work" buttons jump to top after clicking**
This would indicate an outdated version of `frontend.js`. The current version correctly ignores hash-change events in the `popstate` handler so anchor links scroll smoothly without bouncing.

**Re-run the setup wizard**
```bash
wp option delete ds_setup_complete
```
Then visit **Admin → Dashboard → Theme Setup**.

---

## Accessibility

- Semantic landmarks: `<nav>`, `<main>`, `<header>`, `<footer>`, `<aside>`
- ARIA labels on navigation, forms, and interactive elements
- `aria-expanded` / `aria-hidden` on the mobile drawer
- `aria-current="page"` on breadcrumb active item
- `#main-content` anchor for skip-link compatibility
- Fully keyboard-navigable — no focus traps

---

## License

GPL v2 or later — consistent with WordPress licensing.

---

*PHP 8 typed functions · `@wordpress/scripts` block toolchain · WooCommerce theme support · zero runtime JS dependencies on the frontend.*
