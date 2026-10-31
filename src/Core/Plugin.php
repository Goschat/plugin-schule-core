<?php
/**
 * Kern: baut die Module zusammen und lässt die Controller ihre Hooks registrieren.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Core;

use SchulBank\Core\Controller\Cli\SchuleBefehl;
use SchulBank\Core\Model\ModulEinstellungen;
use SchulBank\Core\Modul\Fremdaufrufe;
use SchulBank\Core\Modul\Haertung;
use SchulBank\Core\Modul\Kommentare;
use SchulBank\Core\Modul\Sprache;
use SchulBank\Core\Modul\Termine;
use SchulBank\Core\Modul\Updates;

defined( 'ABSPATH' ) || exit;

final class Plugin {

	public const VERSION = '0.1.0';

	private static ?Plugin $instanz = null;

	/**
	 * @param Modul[] $module
	 */
	private function __construct(
		private string $datei,
		private array $module,
		private ModulEinstellungen $einstellungen
	) {}

	public static function starte( string $datei ): void {
		if ( null !== self::$instanz ) {
			return;
		}

		$verzeichnis = dirname( $datei );
		$view        = new View( $verzeichnis . '/views' );

		$module = array(
			new Haertung(),
			new Fremdaufrufe(),
			new Kommentare(),
			new Sprache(),
			new Termine( $view, $verzeichnis ),
			new Updates( $datei ),
		);

		self::$instanz = new self( $datei, $module, new ModulEinstellungen() );
		self::$instanz->registriere();
	}

	private function registriere(): void {
		$aus = $this->einstellungen->abgeschaltet();

		foreach ( $this->module as $modul ) {
			if ( in_array( $modul->id(), $aus, true ) ) {
				continue;
			}
			foreach ( $modul->controller() as $controller ) {
				$controller->registriere();
			}
		}

		// Der WP-CLI-Befehl ist immer da, damit sich Module wieder einschalten lassen.
		( new SchuleBefehl( $this->module, $this->einstellungen ) )->registriere();
	}
}
