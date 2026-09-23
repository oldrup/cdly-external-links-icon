=== Codeally External Links Icon ===
Contributors: oldrup
Tags: external links, rel external, link icon, block editor, accessibility
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.2
Stable tag: 0.3.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically appends the external link attribute to outward links in post content and displays a link icon using CSS masks.

== Description ==

Codeally External Links Icon automatically detects external links in post content, appends `rel="external"` server-side, and renders an SVG external link icon using pure CSS masks.

= Technical Architecture & Performance =

* **Zero Database Footprint:** Makes no permanent modifications to your post content or database[cite: 3].
* **Zero-Specificity Custom Property API:** Uses `:where(:root)` to expose `--cdly-external-links-icon`, allowing theme stylesheets or Block Editor custom CSS to switch icon presets without specificity conflicts.
* **Page Cache Integration:** Injects `rel="external"` on `the_content` filter before page caching layers (WP Rocket, LiteSpeed, Redis, NGINX FastCGI) capture the final HTML output[cite: 1].
* **Reversible:** Disabling or removing the plugin leaves zero residual data, orphan options, or markup changes in your database[cite: 3].
* **Streaming HTML Parser:** Uses WordPress core `WP_HTML_Tag_Processor` for sub-millisecond execution without creating full DOM trees or memory allocations.
* **Zero Client-Side JavaScript:** Renders icons strictly through browser-native CSS pseudo-elements and data-URI SVG masks without DOM mutations.

= Customizing Icons via CSS =

Switch between built-in presets globally or per-block without database options:

* **Default Box Icon:** `--cdly-mask-image-box`
* **Diagonal Arrow Icon:** `--cdly-mask-image-arrow`

Set `--cdly-external-links-icon: var(--cdly-mask-image-arrow);` in your theme's stylesheet, the Customizer, or directly inside Block Editor Custom CSS (`& { --cdly-external-links-icon: var(--cdly-mask-image-arrow); }`).

= Accessibility & Internationalization (i18n) =

* **Screen Reader Announced:** Uses CSS `content` alternative text syntax (`/ " (external link)"`) so screen readers (NVDA, JAWS, VoiceOver) announce external links naturally without cluttering the DOM with extra HTML `<span>` tags.
* **Fully Translatable:** All user-facing strings and screen reader labels use standard WordPress i18n functions (`__()`) and support custom translations via `.po` / `.mo` files in the `/languages/` folder.
* **High Contrast / WCAG Compliant:** Includes `@media (forced-colors: active)` fallbacks for Windows High Contrast mode (WCAG 1.4.11).

= Compatibility & Baseline Requirements =

* **Content Scope:** Built and tested strictly for native WordPress Core Block Editor content (Paragraphs, Buttons, Lists, etc.)[cite: 5]. Third-party page builders (Elementor, Bricks, Divi) are not officially supported.
* **Browser Baseline:** Uses CSS `:has()` pseudo-class targeting to prevent icons from rendering inside links that wrap images or inline SVGs. Requires modern browsers supporting `:has()` and CSS alt-text (Chrome 105+, Safari 15.4+, Firefox 128+, Edge 105+).

== Installation ==

1. Upload the `cdly-external-links-icon` directory to `/wp-content/plugins/`.
2. Activate the plugin via the 'Plugins' menu in WordPress.
3. External links in post content will automatically receive `rel="external"` and the link icon on the front end.

== Frequently Asked Questions ==

= How do I change the external link icon? =
Set `--cdly-external-links-icon` in your stylesheet or Customizer. For example, to switch to the diagonal arrow icon globally:

`:root { --cdly-external-links-icon: var(--cdly-mask-image-arrow); }`

= Can I change the icon for a specific block or group in the Block Editor? =
Yes. In WordPress 7.0+, open the block settings sidebar, expand **Advanced > Custom CSS**, and add:

`& { --cdly-external-links-icon: var(--cdly-mask-image-arrow); }`

All external links inside that specific block/group will render with the arrow icon.

= Does this plugin edit my database or post content? =
No[cite: 3]. Modifications occur purely in memory during the execution of `the_content` filter. Original database content remains untouched[cite: 3].

= How does this interact with page caching plugins? =
Because `rel="external"` is injected server-side during the initial HTML request, page cache engines store the processed HTML string directly[cite: 1]. Subsequent cached page requests serve the rendered link markup with zero PHP overhead.

= Is this plugin accessible for screen reader users? =
Yes. The plugin utilizes modern CSS alternative text syntax (`content: "\2007" / " (external link)"`) to pass accessible labels directly to the browser accessibility tree without adding visual text or extra HTML spans to your pages.

= How do I translate the "external link" screen reader text into my language? =
The plugin is fully internationalized. You can translate strings using tools like Loco Translate or Poedit. Place your translated `.po` and `.mo` files in the plugin's `/languages/` directory (e.g., `cdly-external-links-icon-da_DK.mo`).

= Does this plugin have a settings page? =
No[cite: 3]. This plugin is intentionally zero-configuration with zero database options[cite: 3]. Icon customization is handled natively through CSS custom properties.

== Changelog ==
= 0.3.0 =
* Added [target="_blank"] and [data-type="link"] to candidates for external links

= 0.2.0 =
* Introduced zero-specificity CSS Custom Property API (`--cdly-external-links-icon`) using `:where(:root)`.
* Added `--cdly-mask-image-arrow` (diagonal arrow) preset alongside `--cdly-mask-image-box` (default box icon).
* Added support for container and block-level icon scoping in Block Editor Custom CSS.

= 0.0.10 =
* Removed redundant `load_plugin_textdomain()` call to support WordPress 4.6+ JIT translations without Plugin Check PCP warnings.

= 0.0.9 =
* Added text domain loading (`Domain Path: /languages`) for i18n translation support.
* Dynamic localized screen reader label injection via `wp_add_inline_style()`.

= 0.0.8 =
* Added screen reader accessibility context directly in CSS `content` syntax.
* Added `forced-colors: active` high-contrast mode media query for WCAG 1.4.11 compliance.
* Precision-tuned `:not(:has(svg, img))` selector and `margin-inline-start` punctuation handling.

= 0.0.7 =
* Updated short description syntax to pass Plugin Check PCP validation.

= 0.0.6 =
* Refactored front-end filtering lifecycle for maximum stability.

= 0.0.5 =
* Tested and optimized domain normalization logic.

= 0.0.4 =
* Improved PHP 8.2+ strict type handling on host comparison helpers.

= 0.0.3 =
* Replaced regex matching engine with WP_HTML_Tag_Processor for high-performance HTML stream parsing.
* Added www domain normalization.

= 0.0.1 =
* Initial standalone plugin release.