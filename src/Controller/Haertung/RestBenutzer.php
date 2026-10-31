<?php
/**
 * Härtung: Benutzerliste der REST-API für nicht Angemeldete sperren (verrät sonst Benutzernamen).
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Haertung;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class RestBenutzer implements Controller {

	public function registriere(): void {
		add_filter( 'rest_endpoints', array( $this, 'entferne_benutzer_endpunkte' ) );
	}

	/**
	 * @param array<string, mixed> $endpunkte
	 * @return array<string, mixed>
	 */
	public function entferne_benutzer_endpunkte( array $endpunkte ): array {
		if ( is_user_logged_in() ) {
			return $endpunkte; // Der Block-Editor braucht die Liste.
		}
		foreach ( array_keys( $endpunkte ) as $route ) {
			if ( str_starts_with( $route, '/wp/v2/users' ) ) {
				unset( $endpunkte[ $route ] );
			}
		}
		return $endpunkte;
	}
}
