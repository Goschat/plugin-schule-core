<?php
/**
 * Modul „Kommentare“: Kommentare und Pingbacks vollständig abschalten.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Modul;

use SchulBank\Core\Controller\Kommentare\KommentareAus;
use SchulBank\Core\Core\Modul;

defined( 'ABSPATH' ) || exit;

final class Kommentare implements Modul {

	public function id(): string {
		return 'kommentare';
	}

	public function beschreibung(): string {
		return 'Kommentare und Pingbacks aus, auch im Backend';
	}

	public function controller(): array {
		return array( new KommentareAus() );
	}
}
