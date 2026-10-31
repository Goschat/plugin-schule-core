<?php
/**
 * Vertrag für Controller: der einzige Ort, an dem Hooks registriert werden.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Core;

defined( 'ABSPATH' ) || exit;

interface Controller {

	/**
	 * Registriert alle Hooks dieses Controllers (add_action, add_filter, Befehle).
	 */
	public function registriere(): void;
}
