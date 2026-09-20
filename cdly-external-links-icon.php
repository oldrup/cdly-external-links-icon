<?php
/**
 * Plugin Name:       Codeally External Links Icon
 * Plugin URI:        https://github.com/oldrup/cdly-external-links-icon
 * Description:       Append rel="external" to all external links in post content and append a link icon via CSS
 * Version:           0.0.7
 * Requires at least: 6.5
 * Requires PHP:      8.2
 * Author:            Codeally
 * Author URI:        https://codeally.dk
 * License:           GPL-2.0-or-later
 * Text Domain:       cdly-external-links-icon
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CDLY_EXTERNAL_LINKS_ICON_VERSION', '0.0.6' );

/**
 * Enqueue plugin styles on the front end.
 */
add_action( 'wp_enqueue_scripts', static function(): void {
	wp_enqueue_style(
		'cdly-external-links-icon',
		plugin_dir_url( __FILE__ ) . 'assets/css/cdly-external-links-icon.css',
		array(),
		CDLY_EXTERNAL_LINKS_ICON_VERSION
	);
} );

/**
 * Determine if a URL targets an external domain.
 */
function cdly_is_external_url( string $href, string $site_host ): bool {
	if ( str_starts_with( $href, '//' ) ) {
		$href = 'https:' . $href;
	}

	$link_host = wp_parse_url( $href, PHP_URL_HOST );
	if ( ! is_string( $link_host ) || '' === $link_host ) {
		return false;
	}

	// Strip www. subdomains for consistent domain comparison
	$norm_link_host = str_starts_with( strtolower( $link_host ), 'www.' ) ? substr( $link_host, 4 ) : $link_host;
	$norm_site_host = str_starts_with( strtolower( $site_host ), 'www.' ) ? substr( $site_host, 4 ) : $site_host;

	return strcasecmp( $norm_link_host, $norm_site_host ) !== 0;
}

/**
 * Parses post content and adds rel="external" to external links using WP_HTML_Tag_Processor.
 */
function cdly_add_external_rel( string $content ): string {
	if ( empty( $content ) || stripos( $content, '<a' ) === false ) {
		return $content;
	}

	$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
	if ( ! is_string( $site_host ) || '' === $site_host ) {
		return $content;
	}

	$processor = new WP_HTML_Tag_Processor( $content );

	while ( $processor->next_tag( array( 'tag_name' => 'A' ) ) ) {
		$href = $processor->get_attribute( 'href' );
		if ( ! is_string( $href ) || '' === trim( $href ) ) {
			continue;
		}

		if ( ! cdly_is_external_url( $href, $site_host ) ) {
			continue;
		}

		$rel       = $processor->get_attribute( 'rel' );
		$rel_str   = is_string( $rel ) ? $rel : '';
		$rel_parts = preg_split( '/\s+/', $rel_str, -1, PREG_SPLIT_NO_EMPTY ) ?: array();

		if ( ! in_array( 'external', $rel_parts, true ) ) {
			$rel_parts[] = 'external';
			$processor->set_attribute( 'rel', implode( ' ', $rel_parts ) );
		}
	}

	return $processor->get_updated_html();
}

/**
 * Attach content filter on non-admin requests.
 */
add_action( 'template_redirect', static function(): void {
	if ( ! is_admin() ) {
		add_filter( 'the_content', 'cdly_add_external_rel', 10 );
	}
} );