<?php
/**
 * Fremdaufrufe: fremde Einbettungen (YouTube, Social Media usw.) erscheinen nur als Link, nie als iframe.
 * Eine 2-Klick-Lösung kommt später als eigenes Modul, falls eine Schule sie braucht.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Fremdaufrufe;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class Einbettungen implements Controller {

	public function registriere(): void {
		add_filter( 'oembed_providers', '__return_empty_array' );
		add_filter( 'embed_oembed_discover', '__return_false' );
		add_filter( 'pre_oembed_result', array( $this, 'nur_eigene_seite' ), 10, 2 );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}

	/**
	 * Eigene Beiträge dürfen weiter eingebettet werden, alles andere nicht.
	 *
	 * @param null|string|false $ergebnis
	 * @return null|string|false false = WordPress zeigt nur den Link.
	 */
	public function nur_eigene_seite( $ergebnis, string $adresse ) {
		$eigener_host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( wp_parse_url( $adresse, PHP_URL_HOST ) === $eigener_host ) {
			return $ergebnis;
		}
		return false;
	}
}
