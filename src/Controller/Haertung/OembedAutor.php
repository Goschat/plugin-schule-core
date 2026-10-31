<?php
/**
 * Härtung: Autorname und Autorenlink aus oEmbed-Antworten entfernen.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Haertung;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class OembedAutor implements Controller {

	public function registriere(): void {
		add_filter( 'oembed_response_data', array( $this, 'entferne_autor' ) );
	}

	/**
	 * @param array<string, mixed> $daten
	 * @return array<string, mixed>
	 */
	public function entferne_autor( array $daten ): array {
		unset( $daten['author_name'], $daten['author_url'] );
		return $daten;
	}
}
