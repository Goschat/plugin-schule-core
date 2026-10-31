<?php
/**
 * Welche Module abgeschaltet sind. Gespeichert als Option sbd_module_aus (Liste von Kurznamen).
 * Geschaltet wird nur per WP-CLI bzw. MainWP, es gibt bewusst keine Oberfläche.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Model;

defined( 'ABSPATH' ) || exit;

final class ModulEinstellungen {

	public const OPTION = 'sbd_module_aus';

	/**
	 * @return string[]
	 */
	public function abgeschaltet(): array {
		$wert = get_option( self::OPTION, array() );
		return is_array( $wert ) ? array_values( array_filter( $wert, 'is_string' ) ) : array();
	}

	public function ist_an( string $id ): bool {
		return ! in_array( $id, $this->abgeschaltet(), true );
	}

	public function schalte( string $id, bool $an ): void {
		$aus = array_diff( $this->abgeschaltet(), array( $id ) );
		if ( ! $an ) {
			$aus[] = $id;
		}
		update_option( self::OPTION, array_values( $aus ), true );
	}
}
