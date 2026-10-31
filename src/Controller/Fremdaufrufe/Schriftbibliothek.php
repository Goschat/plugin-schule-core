<?php
/**
 * Fremdaufrufe: Schriftbibliothek des Editors ohne Google-Schriften und ohne Vorlagen-Verzeichnis von wordpress.org.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Fremdaufrufe;

use SchulBank\Core\Core\Controller;

defined( 'ABSPATH' ) || exit;

final class Schriftbibliothek implements Controller {

	public function registriere(): void {
		add_action( 'init', array( $this, 'ohne_fremde_sammlungen' ), 20 );
		add_filter( 'should_load_remote_block_patterns', '__return_false' );
	}

	public function ohne_fremde_sammlungen(): void {
		if ( function_exists( 'wp_unregister_font_collection' ) ) {
			wp_unregister_font_collection( 'google-fonts' );
		}
	}
}
