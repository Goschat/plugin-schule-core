<?php
/**
 * PSR-4-Autoloader für den Namensraum SchulBank\Core (ohne Composer am Server).
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Core;

defined( 'ABSPATH' ) || exit;

final class Autoloader {

	private const PRAEFIX = 'SchulBank\\Core\\';

	public static function registriere( string $basis ): void {
		spl_autoload_register(
			static function ( string $klasse ) use ( $basis ): void {
				if ( ! str_starts_with( $klasse, self::PRAEFIX ) ) {
					return;
				}
				$relativ = substr( $klasse, strlen( self::PRAEFIX ) );
				$datei   = $basis . '/' . str_replace( '\\', '/', $relativ ) . '.php';
				if ( is_readable( $datei ) ) {
					require_once $datei;
				}
			}
		);
	}
}
