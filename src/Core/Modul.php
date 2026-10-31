<?php
/**
 * Vertrag für Module: ein Modul bündelt Controller und ist einzeln abschaltbar.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Core;

defined( 'ABSPATH' ) || exit;

interface Modul {

	/**
	 * Kurzname für WP-CLI und die Option sbd_module_aus, z. B. "termine".
	 */
	public function id(): string;

	/**
	 * Eine Zeile Beschreibung für "wp schule module".
	 */
	public function beschreibung(): string;

	/**
	 * @return Controller[]
	 */
	public function controller(): array;
}
