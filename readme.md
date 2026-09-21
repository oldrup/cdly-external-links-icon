# Codeally External Links Icon

[![WordPress Version](https://img.shields.io/badge/WordPress-6.5%2B-blue.svg)](https://wordpress.org)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-GPLv2-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Database Footprint](https://img.shields.io/badge/Database%20Footprint-0%20Bytes-brightgreen.svg)](#technical-architecture)

A lightweight, non-destructive WordPress plugin that dynamically appends `rel="external"` to outward links in post content server-side and renders an accessible link icon using pure CSS masks.

---

## Highlights

* **Zero Database Modifications:** Operates entirely in memory during content filtering. Disabling or removing the plugin leaves no residual data, options, or altered post content in your database.
* **Zero-Specificity Design Tokens:** Declares default `--cdly-*` CSS custom properties inside `:where(:root)` with `0,0,0` specificity, enabling global or block-scoped icon overrides without specificity conflicts.
* **Sub-Millisecond Execution:** Built on WordPress core's `WP_HTML_Tag_Processor` streaming parser rather than heavy DOM parsers or regex engines.
* **Zero Client-Side JavaScript:** 100% server-side tag parsing paired with browser-native CSS rendering. No runtime JavaScript, DOM mutations, or client layout shifts.
* **Page Cache Compatible:** Processes content on `the_content` filter before page cache layers (WP Rocket, LiteSpeed, NGINX FastCGI, Redis) cache the HTML output.
* **WCAG 2.1 AA Accessible:** Exposes localized screen reader announcements directly through modern CSS alternative text syntax (`content: "\2007" / " (...)"`) without DOM clutter.

---

## Technical Architecture

### 1. Server-Side HTML Stream Parsing
The plugin hooks into `template_redirect` to run strictly on front-end content views, bypassing WP-Admin, REST API, and AJAX requests entirely. It uses `WP_HTML_Tag_Processor` to parse HTML token-by-token:

### 2. CSS Mask Rendering & Exclusions
Visual icons are rendered via SVG CSS masks. Relational pseudo-selector `:not(:has(svg, img))` prevents icon display on external links that wrap images or inline SVGs:

### 3. Dynamic i18n & Accessible Announcements
Rather than injecting extra HTML `<span>` elements into post content, screen reader labels use CSS Generated Content Module Level 3 alternative text syntax. The localized string is supplied from PHP via `wp_add_inline_style()`:


---

## Requirements & Compatibility

| Requirement | Baseline |
| :--- | :--- |
| **WordPress** | 6.5+ |
| **PHP** | 8.2+ |
| **Browsers** | Chrome 105+, Safari 15.4+, Firefox 128+ (Late 2023+ Baseline for `:has()` and CSS alt-text) |
| **Content Scope** | Core WordPress Block Editor content (Paragraphs, Buttons, Lists, etc.) |

> **Note on Page Builders:** Built specifically for native WordPress Core content filtering. Third-party page builders (Elementor, Bricks, Divi) are not officially supported.

---

## Installation

1. Download or clone this repository into your WordPress plugins directory.
2. Activate **Codeally External Links Icon** via the 'Plugins' menu in WordPress.
3. External links in post content will automatically receive `rel="external"` and the link icon on the front end.

---

## Localization (i18n)

The plugin uses WordPress 4.6+ **Just-In-Time (JIT)** translation loading via the `Domain Path: /languages` header.

To add a new language translation:
1. Copy `languages/cdly-external-links-icon.pot` using tools like Loco Translate or Poedit.
2. Generate language files targeting your locale (e.g., `cdly-external-links-icon-da_DK.po` and `.mo`).
3. Place `.po`, `.mo`, and `.l10n.php` files directly inside the `/languages/` subfolder.


## Customization

Switch between built-in presets globally or per-block without database options:

* **Default Box Icon:** `--cdly-mask-image-box`
* **Diagonal Arrow Icon:** `--cdly-mask-image-arrow`

Set `--cdly-external-links-icon: var(--cdly-mask-image-arrow);` in your theme's stylesheet, the Customizer, or directly inside Block Editor Custom CSS.

---

## License

Distributed under the GNU General Public License v2 or later. See `LICENSE` for details.