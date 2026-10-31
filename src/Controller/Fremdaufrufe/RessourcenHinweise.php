<?php
/**
 * Fremdaufrufe: DNS-Prefetch und Vorverbindungen zu fremden Hosts aus dem Seitenkopf entfernen.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Fremdaufrufe;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class RessourcenHinweise implements Controller {

	public function registriere(): void {
		add_filter( 'wp_resource_hints', array( $this, 'nur_eigener_host' ), 10, 2 );
	}

	/**
	 * @param array<int, string|array<string, string>> $hinweise
	 * @return array<int, string|array<string, string>>
	 */
	public function nur_eigener_host( array $hinweise, string $art ): array {
		if ( ! in_array( $art, array( 'dns-prefetch', 'preconnect' ), true ) ) {
			return $hinweise;
		}
		$eigener_host = wp_parse_url( home_url(), PHP_URL_HOST );

		return array_values(
			array_filter(
				$hinweise,
				static function ( $hinweis ) use ( $eigener_host ): bool {
					$adresse = is_array( $hinweis ) ? ( $hinweis['href'] ?? '' ) : $hinweis;
					return wp_parse_url( $adresse, PHP_URL_HOST ) === $eigener_host;
				}
			)
		);
	}
}
