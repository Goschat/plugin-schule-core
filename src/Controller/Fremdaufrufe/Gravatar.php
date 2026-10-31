<?php
/**
 * Fremdaufrufe: keine Gravatare (Profilbilder von gravatar.com), auch nicht im Backend.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Fremdaufrufe;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class Gravatar implements Controller {

	public function registriere(): void {
		add_filter( 'pre_option_show_avatars', '__return_zero' );
		add_filter( 'pre_get_avatar_data', array( $this, 'ohne_fremde_adresse' ) );
	}

	/**
	 * Falls ein Theme oder Plugin trotzdem ein Avatar-Bild anfordert: keine Adresse liefern.
	 *
	 * @param array<string, mixed> $daten
	 * @return array<string, mixed>
	 */
	public function ohne_fremde_adresse( array $daten ): array {
		$daten['url']          = '';
		$daten['found_avatar'] = false;
		return $daten;
	}
}
