<?php
/**
 * WP-CLI-Befehl „wp schule“: Module anzeigen und schalten (auch über MainWP nutzbar).
 *
 * Beispiele:
 *   wp schule module
 *   wp schule aus kommentare
 *   wp schule an kommentare
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Cli;

use SchulBank\Core\Core\Controller;
use SchulBank\Core\Core\Modul;
use SchulBank\Core\Core\Plugin;
use SchulBank\Core\Model\ModulEinstellungen;

defined( 'ABSPATH' ) || exit;

final class SchuleBefehl implements Controller {

	/**
	 * @param Modul[] $module
	 */
	public function __construct( private array $module, private ModulEinstellungen $einstellungen ) {}

	public function registriere(): void {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			\WP_CLI::add_command( 'schule module', array( $this, 'module' ) );
			\WP_CLI::add_command( 'schule an', array( $this, 'an' ) );
			\WP_CLI::add_command( 'schule aus', array( $this, 'aus' ) );
		}
	}

	/**
	 * Listet alle Module mit Zustand.
	 */
	public function module(): void {
		$zeilen = array();
		foreach ( $this->module as $modul ) {
			$zeilen[] = array(
				'modul'        => $modul->id(),
				'zustand'      => $this->einstellungen->ist_an( $modul->id() ) ? 'an' : 'aus',
				'beschreibung' => $modul->beschreibung(),
			);
		}
		\WP_CLI::log( 'Schule Core ' . Plugin::VERSION );
		\WP_CLI\Utils\format_items( 'table', $zeilen, array( 'modul', 'zustand', 'beschreibung' ) );
	}

	/**
	 * Schaltet ein Modul ein.
	 *
	 * ## OPTIONS
	 *
	 * <modul>
	 * : Kurzname, siehe "wp schule module".
	 *
	 * @param string[] $argumente
	 */
	public function an( array $argumente ): void {
		$this->schalte( $argumente[0] ?? '', true );
	}

	/**
	 * Schaltet ein Modul aus.
	 *
	 * ## OPTIONS
	 *
	 * <modul>
	 * : Kurzname, siehe "wp schule module".
	 *
	 * @param string[] $argumente
	 */
	public function aus( array $argumente ): void {
		$this->schalte( $argumente[0] ?? '', false );
	}

	private function schalte( string $id, bool $an ): void {
		$bekannt = array_map( static fn( Modul $modul ): string => $modul->id(), $this->module );
		if ( ! in_array( $id, $bekannt, true ) ) {
			\WP_CLI::error( sprintf( 'Unbekanntes Modul „%s“. Vorhanden: %s', $id, implode( ', ', $bekannt ) ) );
		}
		$this->einstellungen->schalte( $id, $an );
		\WP_CLI::success( sprintf( 'Modul „%s“ ist jetzt %s.', $id, $an ? 'an' : 'aus' ) );
	}
}
