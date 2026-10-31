<?php
/**
 * Fremdaufrufe: Emoji-Erkennung abschalten (lädt sonst Grafiken von s.w.org).
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Fremdaufrufe;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class Emojis implements Controller {

	public function registriere(): void {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'embed_head', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
		remove_action( 'admin_enqueue_scripts', 'wp_enqueue_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}
}
