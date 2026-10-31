<?php
/**
 * Kommentare und Pingbacks vollständig abschalten: Frontend, Backend, REST-API, Werkzeugleiste.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Kommentare;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class KommentareAus implements Controller {

	public function registriere(): void {
		add_filter( 'comments_open', '__return_false', 20 );
		add_filter( 'pings_open', '__return_false', 20 );
		add_filter( 'comments_array', '__return_empty_array', 20 );
		add_filter( 'rest_endpoints', array( $this, 'ohne_rest_kommentare' ) );
		add_action( 'init', array( $this, 'ohne_unterstuetzung' ), 100 );
		add_action( 'admin_menu', array( $this, 'ohne_menue' ) );
		add_action( 'admin_init', array( $this, 'sperre_kommentarseiten' ) );
		add_action( 'wp_before_admin_bar_render', array( $this, 'ohne_werkzeugleiste' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'ohne_dashboard_widget' ) );
	}

	public function ohne_unterstuetzung(): void {
		foreach ( get_post_types() as $typ ) {
			remove_post_type_support( $typ, 'comments' );
			remove_post_type_support( $typ, 'trackbacks' );
		}
	}

	/**
	 * @param array<string, mixed> $endpunkte
	 * @return array<string, mixed>
	 */
	public function ohne_rest_kommentare( array $endpunkte ): array {
		foreach ( array_keys( $endpunkte ) as $route ) {
			if ( str_starts_with( $route, '/wp/v2/comments' ) ) {
				unset( $endpunkte[ $route ] );
			}
		}
		return $endpunkte;
	}

	public function ohne_menue(): void {
		remove_menu_page( 'edit-comments.php' );
		remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	}

	public function sperre_kommentarseiten(): void {
		global $pagenow;
		if ( in_array( $pagenow, array( 'edit-comments.php', 'comment.php', 'options-discussion.php' ), true ) ) {
			wp_safe_redirect( admin_url() );
			exit;
		}
	}

	public function ohne_werkzeugleiste(): void {
		global $wp_admin_bar;
		if ( $wp_admin_bar instanceof \WP_Admin_Bar ) {
			$wp_admin_bar->remove_node( 'comments' );
		}
	}

	public function ohne_dashboard_widget(): void {
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	}
}
