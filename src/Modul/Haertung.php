<?php
/**
 * Modul „Härtung“: Angriffsfläche verkleinern, Benutzernamen verbergen, Archive aus dem Index.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Modul;

use SchulBank\Core\Controller\Haertung\AnmeldeFehler;
use SchulBank\Core\Controller\Haertung\AutorArchiv;
use SchulBank\Core\Controller\Haertung\DateiEditor;
use SchulBank\Core\Controller\Haertung\Noindex;
use SchulBank\Core\Controller\Haertung\OembedAutor;
use SchulBank\Core\Controller\Haertung\RestBenutzer;
use SchulBank\Core\Controller\Haertung\XmlRpc;
use SchulBank\Core\Core\Modul;

defined( 'ABSPATH' ) || exit;

final class Haertung implements Modul {

	public function id(): string {
		return 'haertung';
	}

	public function beschreibung(): string {
		return 'Benutzerliste, Autorenseiten, XML-RPC, Datei-Editor, Anmeldefehler, noindex für Archive';
	}

	public function controller(): array {
		return array(
			new RestBenutzer(),
			new AutorArchiv(),
			new OembedAutor(),
			new Noindex(),
			new XmlRpc(),
			new DateiEditor(),
			new AnmeldeFehler(),
		);
	}
}
