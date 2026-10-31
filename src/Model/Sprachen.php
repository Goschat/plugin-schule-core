<?php
/**
 * Zweite Sprache der Website neben Deutsch (Option sbd_zweitsprache, z. B. "sl").
 * Gesetzt per WP-CLI: wp option update sbd_zweitsprache sl
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Model;

defined( 'ABSPATH' ) || exit;

final class Sprachen {

	public const OPTION  = 'sbd_zweitsprache';
	public const ERLAUBT = array( 'sl' );

	public function zweitsprache(): string {
		$wert = get_option( self::OPTION, '' );
		return is_string( $wert ) && in_array( $wert, self::ERLAUBT, true ) ? $wert : '';
	}
}
