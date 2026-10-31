<?php
/**
 * Modul „Updates“: Plugin und Basis-Theme über die öffentlichen GitHub-Repos aktualisieren.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Modul;

use SchulBank\Core\Controller\Updates\Aktualisierung;
use SchulBank\Core\Core\Modul;

defined( 'ABSPATH' ) || exit;

final class Updates implements Modul {

	public function __construct( private string $plugin_datei ) {}

	public function id(): string {
		return 'updates';
	}

	public function beschreibung(): string {
		return 'Updates für Plugin und Basis-Theme aus GitHub (Plugin Update Checker)';
	}

	public function controller(): array {
		return array( new Aktualisierung( $this->plugin_datei ) );
	}
}
