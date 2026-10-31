<?php
/**
 * Härtung: Autorenseiten und ?author= auf die Startseite umleiten (Benutzernamen-Ermittlung).
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Haertung;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class AutorArchiv implements Controller {

	public function registriere(): void {
		add_action( 'template_redirect', array( $this, 'leite_um' ), 1 );
		add_filter( 'author_link', array( $this, 'entferne_autorenlink' ) );
	}

	public function leite_um(): void {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- nur Prüfung auf Vorhandensein.
		if ( is_author() || isset( $_GET['author'] ) ) {
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}

	public function entferne_autorenlink(): string {
		return home_url( '/' );
	}
}
