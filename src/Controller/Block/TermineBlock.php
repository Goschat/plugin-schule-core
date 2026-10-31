<?php
/**
 * Block „Nächste Termine“: holt die Termine vom Model, bereitet sie auf und rendert die View.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Block;

use SchulBank\Core\Core\Controller;
use SchulBank\Core\Core\View;
use SchulBank\Core\Model\Sprachen;
use SchulBank\Core\Model\TerminRepository;
use SchulBank\Core\Service\TerminFormat;

defined( 'ABSPATH' ) || exit;

final class TermineBlock implements Controller {

	private const MAX_ANZAHL = 12;

	public function __construct(
		private TerminRepository $repository,
		private TerminFormat $format,
		private Sprachen $sprachen,
		private View $view,
		private string $block_verzeichnis
	) {}

	public function registriere(): void {
		add_action( 'init', array( $this, 'registriere_block' ) );
	}

	public function registriere_block(): void {
		register_block_type(
			$this->block_verzeichnis,
			array( 'render_callback' => array( $this, 'rendere' ) )
		);
	}

	/**
	 * @param array<string, mixed> $attribute
	 */
	public function rendere( array $attribute ): string {
		$anzahl       = min( self::MAX_ANZAHL, max( 1, (int) ( $attribute['anzahl'] ?? 3 ) ) );
		$zweitsprache = $this->sprachen->zweitsprache();
		$termine      = $this->repository->naechste( $anzahl, current_datetime() );

		$daten = array();
		foreach ( $termine as $termin ) {
			$daten[] = $this->format->fuer_ausgabe( $termin, $zweitsprache );
		}

		return $this->view->rendere(
			'blocks/termine',
			array(
				'termine'      => $daten,
				'zweitsprache' => $zweitsprache,
				'leertext'     => (string) ( $attribute['leertext'] ?? '' ),
				'wrapper'      => get_block_wrapper_attributes( array( 'class' => 'sbd-termine' ) ),
			)
		);
	}
}
