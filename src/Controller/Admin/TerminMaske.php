<?php
/**
 * Eingabemaske für Termine im Backend: Felder anzeigen, Eingaben prüfen und speichern.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Admin;

use SchulBank\Core\Core\Controller;
use SchulBank\Core\Core\View;
use SchulBank\Core\Model\TerminEingabe;
use SchulBank\Core\Model\TerminInhaltsart;
use SchulBank\Core\Model\TerminRepository;

defined( 'ABSPATH' ) || exit;

final class TerminMaske implements Controller {

	private const NONCE_FELD   = 'sbd_termin_nonce';
	private const NONCE_AKTION = 'sbd_termin_speichern';
	private const FEHLER_META  = '_sbd_eingabefehler';

	public function __construct( private TerminRepository $repository, private View $view ) {}

	public function registriere(): void {
		add_action( 'add_meta_boxes_' . TerminInhaltsart::TYP, array( $this, 'fuege_maske_hinzu' ) );
		add_action( 'save_post_' . TerminInhaltsart::TYP, array( $this, 'speichere' ), 10, 2 );
		add_action( 'admin_notices', array( $this, 'zeige_fehler' ) );
		add_filter( 'enter_title_here', array( $this, 'titel_platzhalter' ), 10, 2 );
		add_filter( 'manage_' . TerminInhaltsart::TYP . '_posts_columns', array( $this, 'spalten' ) );
		add_action( 'manage_' . TerminInhaltsart::TYP . '_posts_custom_column', array( $this, 'spalteninhalt' ), 10, 2 );
	}

	public function fuege_maske_hinzu(): void {
		add_meta_box( 'sbd-termin', 'Wann und wo', array( $this, 'zeige_maske' ), TerminInhaltsart::TYP, 'normal', 'high' );
	}

	public function zeige_maske( \WP_Post $beitrag ): void {
		$termin = $this->repository->finde( $beitrag->ID );

		$html = $this->view->rendere(
			'admin/termin-maske',
			array(
				'nonce_feld'   => self::NONCE_FELD,
				'nonce_aktion' => self::NONCE_AKTION,
				'beginn_datum' => $termin ? $termin->beginn->format( 'Y-m-d' ) : '',
				'beginn_zeit'  => $termin && ! $termin->ganztags ? $termin->beginn->format( 'H:i' ) : '',
				'ende_datum'   => $termin && $termin->ende ? $termin->ende->format( 'Y-m-d' ) : '',
				'ende_zeit'    => $termin && $termin->ende && ! $termin->ganztags ? $termin->ende->format( 'H:i' ) : '',
				'ort'          => $termin ? $termin->ort : '',
				'beschreibung' => $termin ? $termin->beschreibung : '',
				'max_ort'      => TerminEingabe::MAX_ORT,
				'max_text'     => TerminEingabe::MAX_BESCHREIBUNG,
			)
		);
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Die Vorlage escaped selbst.
	}

	public function speichere( int $id, \WP_Post $beitrag ): void {
		if ( ! isset( $_POST[ self::NONCE_FELD ] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_FELD ] ) ), self::NONCE_AKTION ) ) {
			return;
		}
		if ( wp_is_post_autosave( $id ) || wp_is_post_revision( $id ) || ! current_user_can( 'edit_post', $id ) ) {
			return;
		}

		$roh = array();
		foreach ( array( 'beginn_datum', 'beginn_zeit', 'ende_datum', 'ende_zeit', 'ort' ) as $feld ) {
			$roh[ $feld ] = isset( $_POST[ 'sbd_' . $feld ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'sbd_' . $feld ] ) ) : '';
		}
		$roh['beschreibung'] = isset( $_POST['sbd_beschreibung'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sbd_beschreibung'] ) ) : '';

		$eingabe = TerminEingabe::aus_formular( $roh, wp_timezone() );
		$this->repository->speichere( $id, $eingabe );

		if ( $eingabe->ist_gueltig() ) {
			delete_post_meta( $id, self::FEHLER_META );
		} else {
			update_post_meta( $id, self::FEHLER_META, $eingabe->fehler );
		}
	}

	public function zeige_fehler(): void {
		$bildschirm = get_current_screen();
		if ( ! $bildschirm || TerminInhaltsart::TYP !== $bildschirm->post_type || 'post' !== $bildschirm->base ) {
			return;
		}
		$id = get_the_ID();
		if ( ! $id ) {
			return;
		}
		$fehler = get_post_meta( $id, self::FEHLER_META, true );
		if ( ! is_array( $fehler ) || array() === $fehler ) {
			return;
		}
		delete_post_meta( $id, self::FEHLER_META );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Die Vorlage escaped selbst.
		echo $this->view->rendere( 'admin/hinweis-fehler', array( 'fehler' => $fehler ) );
	}

	public function titel_platzhalter( string $text, \WP_Post $beitrag ): string {
		return TerminInhaltsart::TYP === $beitrag->post_type ? 'Was findet statt? (z. B. Elternabend 1. Klassen)' : $text;
	}

	/**
	 * @param array<string, string> $spalten
	 * @return array<string, string>
	 */
	public function spalten( array $spalten ): array {
		$neu = array();
		foreach ( $spalten as $schluessel => $name ) {
			$neu[ $schluessel ] = $name;
			if ( 'title' === $schluessel ) {
				$neu['sbd_beginn'] = 'Beginn';
				$neu['sbd_ort']    = 'Ort';
			}
		}
		unset( $neu['date'] );
		return $neu;
	}

	public function spalteninhalt( string $spalte, int $id ): void {
		$termin = $this->repository->finde( $id );
		if ( null === $termin ) {
			return;
		}
		if ( 'sbd_beginn' === $spalte ) {
			echo esc_html( $termin->beginn->format( $termin->ganztags ? 'd.m.Y' : 'd.m.Y, H:i' ) );
		} elseif ( 'sbd_ort' === $spalte ) {
			echo esc_html( $termin->ort );
		}
	}
}
