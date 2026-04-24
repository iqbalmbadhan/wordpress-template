# Dawn Simmons — WordPress Portfolio Theme

A professional, dark-mode WordPress theme for ServiceNow consultants and AI transformation experts. Fully editable via the **Block Editor (Gutenberg)** or **Elementor**, with WooCommerce support, a first-run setup wizard, and React-powered Gutenberg blocks.

---

## Repository Structure

```
wordpress-template/
├── wordpress/
│   └── dawn-simmons/        ← Install this as your WordPress theme
│       ├── style.css            Theme header & identity
│       ├── theme.json           Block editor design tokens
│       ├── functions.php        Core bootstrap
│       ├── header.php / footer.php
│       ├── front-page.php       Homepage template
│       ├── page.php             Default page template
│       ├── single.php           Single post template
│       ├── archive.php          Blog archive template
│       ├── 404.php              404 template
│       ├── assets/
│       │   ├── css/
│       │   │   ├── main.css         Full theme stylesheet
│       │   │   ├── editor.css       Block editor styles
│       │   │   └── woocommerce.css  WooCommerce overrides
│       │   └── js/
│       │       ├── frontend.js      Nav, animations, TOC, filters
│       │       └── blocks/          ← Output of `npm run build` (see below)
│       ├── inc/
│       │   ├── class-plugin-checker.php   Plugin notice system
│       │   ├── class-setup-wizard.php     First-run wizard
│       │   ├── class-demo-importer.php    Demo content creator
│       │   ├── enqueue.php                Asset enqueuing
│       │   ├── customizer.php             Theme options
│       │   ├── template-functions.php     Helper functions
│       │   ├── blocks/                    Gutenberg block registration + PHP renderers
│       │   │   ├── register-blocks.php
│       │   │   ├── hero/block.json
│       │   │   ├── services/block.json
│       │   │   ├── ai-section/block.json
│       │   │   ├── about/block.json
│       │   │   ├── testimonials/block.json
│       │   │   └── contact/block.json
│       │   └── elementor/
│       │       ├── class-elementor-manager.php
│       │       └── widgets/
│       │           ├── class-widget-hero.php
│       │           ├── class-widget-ai-section.php
│       │           ├── class-widget-services.php
│       │           ├── class-widget-about.php
│       │           ├── class-widget-testimonials.php
│       │           └── class-widget-contact.php
│       └── woocommerce/
│           ├── archive-product.php   Shop page template
│           └── single-product.php    Product page template
│
├── blocks/                  ← React source for Gutenberg block editor UI
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
└── src/                     ← React + Vite standalone demo app
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

In WordPress Admin → **Appearance → Themes**, activate **Dawn Simmons Portfolio**.

---

### Step 2 — First-Run Setup Wizard

After activation, WordPress automatically redirects to the **Setup Wizard**:

```
WP Admin → Dashboard → Theme Setup
```

#### Wizard Step 1 — Install Plugins

| Plugin | Type | Purpose |
|---|---|---|
| WooCommerce | Required | Shop, cart, checkout, product pages |
| Elementor | Recommended | Drag-and-drop visual builder |
| Contact Form 7 | Recommended | Contact section forms |

Click **Install** or **Activate** next to each plugin directly from the wizard page.

#### Wizard Step 2 — Choose Your Editor

- **Block Editor (Gutenberg)** — WordPress's native editor. No plugins needed. All 6 sections are custom blocks with full inspector controls.
- **Elementor** — Visual drag-and-drop builder. Requires the Elementor plugin (free). All sections appear in a dedicated **Dawn Simmons** widget category.

> You can change this preference later at **Appearance → Customize → Editor Settings**.

#### Wizard Step 3 — Import Demo Content

Auto-creates everything in one click:
- Pages: Home, Blog, About, Services, Contact
- Primary and footer navigation menus
- 3 sample blog posts with categories
- WooCommerce shop pages (if WooCommerce is active)
- Full homepage content — blocks or Elementor layout, matching the wizard's editor choice

#### Wizard Step 4 — Done

Links to view your live site and return to the dashboard.

---

### Step 3 — Build Block Editor Controls (Optional)

The theme renders entirely via **PHP** — no build step is required for the frontend. Pages look correct from install.

The build step adds **sidebar inspector panels** in the Block Editor (text, image, and settings controls visible in the right-hand panel while editing).

```bash
cd blocks
npm install
npm run build
```

Output goes to `wordpress/dawn-simmons/assets/js/blocks/index.js`. The theme loads it automatically when the file is present.

Watch mode for development:

```bash
npm run start
```

---

## Customization

### WordPress Customizer

**Appearance → Customize** exposes:

| Control | Options |
|---|---|
| Accent Color | Teal (default) · Violet · Gold · Coral |
| Background Theme | Dark (default) · Midnight · Warm |
| Font Pair | Playfair + DM Sans (default) · Geometric · Editorial |
| Logo Text | Text shown in the navbar logo area |
| CTA Button Text | Navbar call-to-action button label |
| Footer Copyright | Footer copy line |

Changes apply instantly via CSS custom properties in `<head>` — no cache clearing needed.

---

### Block Editor

Each homepage block has a full **Settings panel** in the right sidebar:

| Block | Editable Fields |
|---|---|
| **Hero** | Eyebrow, heading, subheading, role bullets, stats (number/suffix/label), CTA buttons, profile photo |
| **AI Section** | Headline, lead text, capability pills (one per line), flow steps, feature cards |
| **Services** | Eyebrow, title, 6 service cards (number, title, description, tags) |
| **About** | Bio paragraphs, profile photo, skill bars (name + %), details grid |
| **Testimonials** | Eyebrow, title, up to N testimonial cards (quote, name, role) |
| **Contact** | Eyebrow, title, subtitle, email, location, response time, Contact Form 7 ID |

---

### Elementor

After activating Elementor, find all sections in the widget panel under **Dawn Simmons**. Every widget has the same controls listed above, plus Elementor's full styling options (margin, padding, typography, color overrides).

The homepage demo data is imported as a complete Elementor page layout — open the page in Elementor to edit any section visually.

---

## Pages & Templates

| Template File | Used For |
|---|---|
| `front-page.php` | Static homepage (Gutenberg blocks or Elementor layout) |
| `page.php` | Standard pages (About, Services, Contact) |
| `single.php` | Blog posts — breadcrumb, reading time, TOC, author, share, related posts |
| `archive.php` | Blog archive — post grid with sidebar and category filter |
| `404.php` | Custom 404 page with links to Home and Blog |
| `woocommerce/archive-product.php` | Shop / product grid |
| `woocommerce/single-product.php` | Single product page |

---

## WooCommerce

Full WooCommerce support is declared out of the box:

- Product gallery zoom, lightbox, and slider
- Dark-mode product grid (3 columns → 2 → 1 at breakpoints)
- Styled cart table, checkout form, notices, and breadcrumbs
- All WooCommerce pages created automatically by the setup wizard

The WooCommerce stylesheet loads only when WooCommerce is active — no conflicts on non-shop installs.

---

## Navigation & Menus

Two menu locations:

| Location | Handle | Typical items |
|---|---|---|
| Primary Navigation | `primary` | Home, Services, About, Blog, Shop, Contact |
| Footer Navigation | `footer` | Blog, About, Contact |

Assign menus at **Appearance → Menus**. The demo importer assigns them automatically.

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

`assets/js/frontend.js` — zero dependencies, vanilla JS:

| Feature | How it works |
|---|---|
| Mobile nav drawer | Hamburger toggle with `aria-expanded` |
| Sticky navbar | `scrolled` class after 60px scroll |
| Active nav link | Scroll-spy on `section[id]` elements |
| Fade-in on scroll | `IntersectionObserver` on `.fade-in` elements |
| Counter animation | `IntersectionObserver` on `.counter[data-target]` |
| Skill bar fill | `IntersectionObserver` on `.skill-fill[data-width]` |
| Table of contents | Scroll-spy + smooth scroll for `.toc-item a` |
| Archive category filter | Click on `.filter-pill[data-cat]` |
| Contact form fallback | Native form submit handler (when CF7 not active) |

---

## Design Tokens (CSS Variables)

All component styles reference these variables — change theme settings in the Customizer and they update automatically:

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

## React Usage

There are **two separate React projects** in this repository. They are independent — use one, both, or neither depending on your workflow.

---

### Project 1 — Gutenberg Block Editor UI (`blocks/`)

This is a **WordPress-native React build** using `@wordpress/scripts`. It adds sidebar inspector panels to the six custom blocks inside the WordPress Block Editor. The frontend (what visitors see) always renders via PHP — this build only affects the editing experience inside WordPress Admin.

#### When you need it

- You want to edit homepage section content (text, images, stats, testimonials, etc.) through the WordPress Block Editor sidebar instead of directly modifying PHP or re-running the demo importer.
- You see blocks on the homepage but no controls appear in the right-hand panel when you select one.

#### Setup

```bash
cd blocks
npm install
npm run build
```

The compiled file is written to:

```
wordpress/dawn-simmons/assets/js/blocks/index.js
```

The theme loads it automatically when the file exists. **No changes to WordPress or PHP are needed.**

#### Development (watch mode)

```bash
cd blocks
npm run start
```

Watches `blocks/src/` and rebuilds on every save. Keep this running while editing block controls.

#### How it works

Each block has its own file at `blocks/src/blocks/{block-name}/index.js`. The file calls `registerBlockType()` with:

- **`metadata`** — imported directly from the corresponding `block.json` (attributes, name, category, icon)
- **`edit()`** — a React component using `@wordpress/components` (`TextControl`, `TextareaControl`, `PanelBody`, etc.) that renders the inspector sidebar UI. Changes call `setAttributes()` to update block data.
- **`save()`** — returns `null` because all frontend HTML is produced by the PHP render callback in `inc/blocks/register-blocks.php`

Example structure of a block file:

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
                        {/* more controls… */}
                    </PanelBody>
                </InspectorControls>
                {/* optional canvas preview */}
            </>
        );
    },

    save: () => null,   // PHP renders the frontend
});
```

#### Adding a control to an existing block

1. Open `blocks/src/blocks/{block-name}/index.js`
2. Destructure the new attribute from `attributes`
3. Add a `TextControl` / `TextareaControl` / `ToggleControl` inside `<InspectorControls>`
4. Add the attribute to the matching `block.json` with a `type` and `default`
5. Read it in the PHP render callback in `inc/blocks/register-blocks.php`
6. Run `npm run build` (or keep `npm run start` running)

#### Linting

```bash
cd blocks
npm run lint:js
```

---

### Project 2 — Standalone React + Vite App (`src/`)

This is a **fully self-contained React application** that reproduces the entire Dawn Simmons portfolio site — no WordPress required. It is useful for:

- Live previewing the design and content before importing into WordPress
- Sharing a static demo with clients (deploy to Netlify, Vercel, GitHub Pages, etc.)
- Rapidly prototyping layout changes without touching WordPress

#### Tech stack

| Package | Purpose |
|---|---|
| `react` + `react-dom` 18 | UI rendering |
| `react-router-dom` 6 | Client-side routing (HashRouter) |
| `vite` 6 | Dev server and bundler |
| `@vitejs/plugin-react` | JSX transform + fast refresh |

#### Setup

```bash
# from the repo root
npm install
npm run dev
```

Open `http://localhost:5173` in your browser.

#### Available commands

| Command | What it does |
|---|---|
| `npm run dev` | Start local dev server with hot module reload |
| `npm run build` | Production build → `dist/` |
| `npm run preview` | Serve the `dist/` build locally for final checks |

#### Routes

| URL (hash) | Component | Description |
|---|---|---|
| `/#/` | `src/pages/HomePage.jsx` | Full portfolio — all 6 sections |
| `/#/blog` | `src/pages/BlogPage.jsx` | Blog post grid |
| `/#/article` | `src/pages/ArticlePage.jsx` | Single article view |

#### Project layout

```
src/
├── main.jsx               Entry point — mounts <App /> to #root
├── App.jsx                Router + ThemeProvider wrapper
├── context/
│   └── ThemeContext.jsx   Global accent / background / font state
├── pages/
│   ├── HomePage.jsx       Hero, AI, Services, About, Testimonials, Contact
│   ├── BlogPage.jsx       Post grid with category filter
│   └── ArticlePage.jsx    Single post with TOC and related posts
└── components/
    ├── Navbar.jsx         Sticky nav with scroll-spy and mobile drawer
    ├── Footer.jsx         Footer with links and copyright
    └── EditorPanel.jsx    Live tweaks panel (accent/bg/font switcher)
```

#### Theme context

`ThemeContext` holds `accent`, `bg`, and `font` values and exposes a `setTheme()` helper. The `EditorPanel` component calls it to apply changes instantly via CSS variables on `:root`. Any component can consume the context:

```jsx
import { useTheme } from '../context/ThemeContext';

function MyComponent() {
    const { accent, setTheme } = useTheme();
    return (
        <button onClick={() => setTheme({ accent: 'violet' })}>
            Switch to Violet
        </button>
    );
}
```

#### Deploying the static build

```bash
npm run build          # outputs to dist/
```

Deploy the `dist/` folder to any static host. Because the app uses `HashRouter`, all routes (`/#/blog`, etc.) work without server-side redirect rules.

For Netlify / Vercel: point the publish directory to `dist/`. No `_redirects` file needed with hash routing.

---

### Which React project should I use?

| Goal | Use |
|---|---|
| Edit block content in WordPress Admin | `blocks/` (Gutenberg build) |
| Preview / demo the site without WordPress | `src/` (Vite app) |
| Deploy a static portfolio site | `src/` → `npm run build` → deploy `dist/` |
| Add a new editable field to a block | `blocks/` + `block.json` + PHP render callback |
| Build a headless frontend consuming the WP REST API | `src/` + `GET /wp-json/dawn-simmons/v1/settings` |



```
GET /wp-json/dawn-simmons/v1/settings
```

Returns the active theme configuration — useful for headless or decoupled React components:

```json
{
  "accent":      "teal",
  "bg":          "dark",
  "font":        "playfair",
  "editor_pref": "gutenberg"
}
```

---

## Custom Post Types

| Type | Slug | Notes |
|---|---|---|
| Services | `ds_service` | Public, REST-enabled, supports title/editor/thumbnail/excerpt |
| Testimonials | `ds_testimonial` | Private (admin only), REST-enabled |

---

## WP-CLI Reference

```bash
# Re-run demo content import
wp eval 'DS_Demo_Importer::run();'

# Check current editor preference
wp option get ds_editor_preference

# Switch editor preference
wp option update ds_editor_preference elementor

# Reset setup wizard (will redirect on next admin visit)
wp option delete ds_setup_complete

# Flush Elementor CSS cache after theme changes
wp eval '\Elementor\Plugin::$instance->files_manager->clear_cache();'

# Flush all caches
wp cache flush
wp rewrite flush
```

---

## Troubleshooting

**Homepage looks blank after import**
Go to **Settings → Reading** and confirm *A static page* is selected, with **Home** as Homepage and **Blog** as Posts page.

**Block editor shows no sidebar controls for custom blocks**
Run `cd blocks && npm install && npm run build`. The frontend renders via PHP regardless — the build only enables the editor sidebar UI.

**Elementor widgets don't appear in the panel**
Make sure Elementor is active *and* the editor preference is set to Elementor. Widgets are registered only when `ELEMENTOR_VERSION` is defined.

**Contact form doesn't send email**
Install and activate **Contact Form 7**, create a form, copy its ID, and set it in the Contact block/widget's CF7 ID field. Without CF7, the built-in native form is used (requires PHP `mail()` to work on your host).

**WooCommerce pages are unstyled**
Verify WooCommerce is active. The `ds-woocommerce` stylesheet loads conditionally — deactivating WooCommerce removes it.

**Re-run the setup wizard**
```bash
wp option delete ds_setup_complete
```
Then visit Admin → Dashboard → Theme Setup.

---

## Accessibility

- Semantic landmarks: `<nav>`, `<main>`, `<header>`, `<footer>`, `<aside>`
- ARIA labels on navigation, forms, and interactive elements
- `aria-expanded` / `aria-hidden` on the mobile drawer
- `aria-current="page"` on breadcrumb active item
- `#main-content` anchor for skip-link compatibility
- Keyboard-navigable — no focus traps

---

## License

GPL v2 or later — consistent with WordPress licensing.

---

*Built to senior-engineer standards: PHP 8 typed functions · `@wordpress/scripts` block toolchain · Elementor Widget API · WooCommerce theme support · zero runtime JS dependencies on the frontend.*
