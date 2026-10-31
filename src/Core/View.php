<?php
/**
 * Rendert PHP-Vorlagen aus views/. Vorlagen bekommen nur das Array $daten
 * und geben aus; sie fragen nichts ab und treffen keine Entscheidungen.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Core;

defined( 'ABSPATH' ) || exit;

final class View {

	public function __construct( private string $verzeichnis ) {}

	/**
	 * @param string               $vorlage Pfad relativ zu views/, ohne .php, z. B. "blocks/termine".
	 * @param array<string, mixed> $daten   Für die Vorlage aufbereitete Daten.
	 */
	public function rendere( string $vorlage, array $daten = array() ): string {
		if ( ! preg_match( '#^[a-z0-9-]+(/[a-z0-9-]+)*$#', $vorlage ) ) {
			return '';
		}
		$datei = $this->verzeichnis . '/' . $vorlage . '.php';
		if ( ! is_readable( $datei ) ) {
			return '';
		}

		ob_start();
		( static function ( string $datei, array $daten ): void { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter -- $daten ist die Schnittstelle zur Vorlage.
			require $datei;
		} )( $datei, $daten );
		return (string) ob_get_clean();
	}
}
