<?php
/**
 * Modul „Fremdaufrufe“: keine Verbindungen von Besucherinnen und Besuchern zu fremden Servern.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Modul;

use SchulBank\Core\Controller\Fremdaufrufe\Einbettungen;
use SchulBank\Core\Controller\Fremdaufrufe\Emojis;
use SchulBank\Core\Controller\Fremdaufrufe\Gravatar;
use SchulBank\Core\Controller\Fremdaufrufe\RessourcenHinweise;
use SchulBank\Core\Controller\Fremdaufrufe\Schriftbibliothek;
use SchulBank\Core\Core\Modul;

defined( 'ABSPATH' ) || exit;

final class Fremdaufrufe implements Modul {

	public function id(): string {
		return 'fremdaufrufe';
	}

	public function beschreibung(): string {
		return 'Emoji-Skript, Gravatare, DNS-Prefetch, fremde Einbettungen, Google-Schriften aus der Schriftbibliothek';
	}

	public function controller(): array {
		return array(
			new Emojis(),
			new Gravatar(),
			new RessourcenHinweise(),
			new Einbettungen(),
			new Schriftbibliothek(),
		);
	}
}
