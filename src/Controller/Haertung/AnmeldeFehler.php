<?php
/**
 * Härtung: allgemeine Fehlermeldung bei der Anmeldung (verrät nicht, ob der Benutzername stimmt).
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Haertung;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class AnmeldeFehler implements Controller {

	private const VERRATENDE_CODES = array( 'invalid_username', 'invalid_email', 'incorrect_password' );

	public function registriere(): void {
		add_filter( 'wp_login_errors', array( $this, 'allgemeine_meldung' ) );
	}

	/**
	 * Ersetzt nur die Meldungen, die verraten, ob ein Konto existiert.
	 * Andere Meldungen (z. B. vom Zwei-Faktor-Plugin) bleiben unverändert.
	 */
	public function allgemeine_meldung( \WP_Error $fehler ): \WP_Error {
		$verraeterisch = array_intersect( $fehler->get_error_codes(), self::VERRATENDE_CODES );
		if ( array() === $verraeterisch ) {
			return $fehler;
		}
		foreach ( $verraeterisch as $code ) {
			$fehler->remove( $code );
		}
		$fehler->add( 'sbd_anmeldung', '<strong>Anmeldung fehlgeschlagen.</strong> Benutzername oder Passwort stimmen nicht.' );
		return $fehler;
	}
}
