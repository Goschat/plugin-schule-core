<?php
/**
 * Härtung: Autoren-, Datums-, Schlagwort- und Suchseiten aus dem Suchindex, Sitemap entsprechend filtern.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Haertung;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class Noindex implements Controller {

	public function registriere(): void {
		add_filter( 'wp_robots', array( $this, 'robots' ) );
		add_filter( 'wp_sitemaps_add_provider', array( $this, 'ohne_benutzer_sitemap' ), 10, 2 );
		add_filter( 'wp_sitemaps_taxonomies', array( $this, 'ohne_schlagwoerter' ) );
	}

	/**
	 * @param array<string, mixed> $robots
	 * @return array<string, mixed>
	 */
	public function robots( array $robots ): array {
		if ( is_author() || is_date() || is_tag() || is_search() ) {
			$robots['noindex'] = true;
			$robots['follow']  = true;
		}
		return $robots;
	}

	/**
	 * @param \WP_Sitemaps_Provider|false $anbieter
	 * @return \WP_Sitemaps_Provider|false
	 */
	public function ohne_benutzer_sitemap( $anbieter, string $name ) {
		return 'users' === $name ? false : $anbieter;
	}

	/**
	 * @param array<string, \WP_Taxonomy> $taxonomien
	 * @return array<string, \WP_Taxonomy>
	 */
	public function ohne_schlagwoerter( array $taxonomien ): array {
		unset( $taxonomien['post_tag'] );
		return $taxonomien;
	}
}
