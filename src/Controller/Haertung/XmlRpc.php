<?php
/**
 * Härtung: XML-RPC und Pingback-Hinweise abschalten (MainWP braucht XML-RPC nicht).
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Haertung;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class XmlRpc implements Controller {

	public function registriere(): void {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		add_filter( 'xmlrpc_methods', '__return_empty_array' );
		add_filter( 'wp_headers', array( $this, 'ohne_pingback_header' ) );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wp_generator' );
	}

	/**
	 * @param array<string, string> $header
	 * @return array<string, string>
	 */
	public function ohne_pingback_header( array $header ): array {
		unset( $header['X-Pingback'] );
		return $header;
	}
}
