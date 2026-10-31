<?php
/**
 * Modul „Termine“: Inhaltsart Termin mit Eingabemaske und Block „Nächste Termine“.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Modul;

use SchulBank\Core\Controller\Admin\TerminMaske;
use SchulBank\Core\Controller\Block\TermineBlock;
use SchulBank\Core\Controller\Inhaltsart\TerminRegistrierung;
use SchulBank\Core\Core\Modul;
use SchulBank\Core\Core\View;
use SchulBank\Core\Model\Sprachen;
use SchulBank\Core\Model\TerminRepository;
use SchulBank\Core\Service\TerminFormat;

defined( 'ABSPATH' ) || exit;

final class Termine implements Modul {

	public function __construct( private View $view, private string $verzeichnis ) {}

	public function id(): string {
		return 'termine';
	}

	public function beschreibung(): string {
		return 'Inhaltsart Termin, Eingabemaske mit Datumswähler, Block „Nächste Termine“';
	}

	public function controller(): array {
		$repository = new TerminRepository();
		$format     = new TerminFormat();

		return array(
			new TerminRegistrierung(),
			new TerminMaske( $repository, $this->view ),
			new TermineBlock( $repository, $format, new Sprachen(), $this->view, $this->verzeichnis . '/blocks/termine' ),
		);
	}
}
