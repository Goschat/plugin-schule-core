<?php
/**
 * Modul „Sprache“: slowenische Abschnitte für Screenreader als Slowenisch auszeichnen.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Modul;

use SchulBank\Core\Controller\Sprache\SlowenischMarkierung;
use SchulBank\Core\Core\Modul;

defined( 'ABSPATH' ) || exit;

final class Sprache implements Modul {

	public function id(): string {
		return 'sprache';
	}

	public function beschreibung(): string {
		return 'Blockstil „Slowenisch“ setzt lang="sl" für Absätze, Überschriften, Listen und Gruppen';
	}

	public function controller(): array {
		return array( new SlowenischMarkierung() );
	}
}
