<?php
/**
 * Registriert die Inhaltsart „Termin“ bei WordPress.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Inhaltsart;

use SchulBank\Core\Core\Controller;
use SchulBank\Core\Model\TerminInhaltsart;

defined( 'ABSPATH' ) || exit;

final class TerminRegistrierung implements Controller {

	public function registriere(): void {
		add_action( 'init', array( $this, 'registriere_inhaltsart' ) );
	}

	public function registriere_inhaltsart(): void {
		register_post_type( TerminInhaltsart::TYP, TerminInhaltsart::argumente() );
	}
}
