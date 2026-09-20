=== Codeally External Links Icon ===
Contributors: oldrup
Tags: external links, rel external, link icon, block editor, performance
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.2
Stable tag: 0.0.7
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically appends the external link attribute to outward links in post content and displays a link icon using CSS masks.

== Description ==

Codeally External Links Icon automatically detects external links in post content, appends `rel="external"` server-side, and renders an SVG external link icon using pure CSS masks.

= Technical Architecture & Performance =

* **Zero Database Footprint:** Makes no permanent modifications to your post content or database[cite: 3].
* **Page Cache Integration:** Injects `rel="external"` on `the_content` filter before page caching layers (WP Rocket, LiteSpeed, Redis, NGINX FastCGI) capture the final HTML output[cite: 1, 7].
* **Reversible:** Disabling or removing the plugin leaves zero residual data, orphan options, or markup changes in your database[cite: 3].
* **Streaming HTML Parser:** Uses WordPress core `WP_HTML_Tag_Processor` for sub-millisecond execution without creating full DOM trees or memory allocations.
* **Zero Client-Side JavaScript:** Renders icons strictly through browser-native CSS pseudo-elements and data-URI SVG masks without DOM mutations.

= Compatibility & Baseline Requirements =

* **Content Scope:** Built and tested strictly for native WordPress Core Block Editor content (Paragraphs, Buttons, Lists, etc.)[cite: 5]. Third-party page builders (Elementor, Bricks, Divi) are not officially supported.
* **Browser Baseline:** Uses CSS `:has()` pseudo-class targeting to prevent icons from rendering inside links that wrap images or inline SVGs. Requires modern browsers supporting `:has()` (Chrome 105+, Safari 15.4+, Firefox 121+, Edge 105+ / Late 2023+ Baseline).

== Installation ==

1. Upload the `cdly-external-links-icon` directory to `/wp-content/plugins/`.
2. Activate the plugin via the 'Plugins' menu in WordPress.
3. External links in post content will automatically receive `rel="external"` and the link icon on the front end.

== Frequently Asked Questions ==

= Does this plugin edit my database or post content? =
No[cite: 3]. Modifications occur purely in memory during the execution of `the_content` filter. Original database content remains untouched[cite: 3].

= How does this interact with page caching plugins? =
Because `rel="external"` is injected server-side during the initial HTML request, page cache engines store the processed HTML string directly[cite: 1, 7]. Subsequent cached page requests serve the rendered link markup with zero PHP overhead.

= Is this compatible with page builders like Elementor or Bricks? =
This plugin specifically targets core WordPress content filtering. While it may work with builders that adhere strictly to `the_content` filter pipeline, third-party page builders are not officially tested or supported.

= Does this plugin have a settings page? =
No[cite: 3]. This plugin is intentionally zero-configuration with zero database options[cite: 3].

= Which browsers support the external icon? =
The icon relies on CSS mask-image and the `:has()` relational selector. Supported in all major browsers released since late 2023 (Chrome 105+, Safari 15.4+, Firefox 121+).

== Changelog ==

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