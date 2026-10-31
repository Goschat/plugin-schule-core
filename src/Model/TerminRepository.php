<?php
/**
 * Einziger Ort, an dem Termine gelesen und gespeichert werden.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Model;

defined( 'ABSPATH' ) || exit;

final class TerminRepository {

	/**
	 * Termine, die noch nicht vorbei sind: Beginn heute oder später, oder Ende noch nicht erreicht.
	 *
	 * @return Termin[]
	 */
	public function naechste( int $anzahl, \DateTimeImmutable $jetzt ): array {
		$heute = $jetzt->setTime( 0, 0 );

		$abfrage = new \WP_Query(
			array(
				'post_type'              => TerminInhaltsart::TYP,
				'post_status'            => 'publish',
				'posts_per_page'         => max( 1, $anzahl ),
				'no_found_rows'          => true,
				'update_post_term_cache' => false,
				'meta_key'               => TerminInhaltsart::META_BEGINN, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'orderby'                => 'meta_value',
				'order'                  => 'ASC',
				'meta_query'             => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'relation' => 'OR',
					array(
						'key'     => TerminInhaltsart::META_BEGINN,
						'value'   => $heute->format( TerminInhaltsart::DB_FORMAT ),
						'compare' => '>=',
						'type'    => 'DATETIME',
					),
					array(
						'key'     => TerminInhaltsart::META_ENDE,
						'value'   => $jetzt->format( TerminInhaltsart::DB_FORMAT ),
						'compare' => '>=',
						'type'    => 'DATETIME',
					),
				),
			)
		);

		$termine = array();
		foreach ( $abfrage->posts as $beitrag ) {
			$termin = $this->aus_beitrag( $beitrag );
			if ( null !== $termin ) {
				$termine[] = $termin;
			}
		}
		return $termine;
	}

	public function finde( int $id ): ?Termin {
		$beitrag = get_post( $id );
		if ( ! $beitrag instanceof \WP_Post || TerminInhaltsart::TYP !== $beitrag->post_type ) {
			return null;
		}
		return $this->aus_beitrag( $beitrag );
	}

	public function speichere( int $id, TerminEingabe $eingabe ): void {
		if ( null === $eingabe->beginn ) {
			return;
		}
		update_post_meta( $id, TerminInhaltsart::META_BEGINN, $eingabe->beginn->format( TerminInhaltsart::DB_FORMAT ) );
		update_post_meta( $id, TerminInhaltsart::META_GANZTAGS, $eingabe->ganztags ? '1' : '0' );
		update_post_meta( $id, TerminInhaltsart::META_ORT, $eingabe->ort );
		update_post_meta( $id, TerminInhaltsart::META_BESCHREIBUNG, $eingabe->beschreibung );

		if ( null === $eingabe->ende ) {
			delete_post_meta( $id, TerminInhaltsart::META_ENDE );
		} else {
			update_post_meta( $id, TerminInhaltsart::META_ENDE, $eingabe->ende->format( TerminInhaltsart::DB_FORMAT ) );
		}
	}

	private function aus_beitrag( \WP_Post $beitrag ): ?Termin {
		$zone   = wp_timezone();
		$beginn = $this->zeitpunkt( (string) get_post_meta( $beitrag->ID, TerminInhaltsart::META_BEGINN, true ), $zone );
		if ( null === $beginn ) {
			return null;
		}

		return new Termin(
			$beitrag->ID,
			get_the_title( $beitrag ),
			$beginn,
			'1' === get_post_meta( $beitrag->ID, TerminInhaltsart::META_GANZTAGS, true ),
			$this->zeitpunkt( (string) get_post_meta( $beitrag->ID, TerminInhaltsart::META_ENDE, true ), $zone ),
			(string) get_post_meta( $beitrag->ID, TerminInhaltsart::META_ORT, true ),
			(string) get_post_meta( $beitrag->ID, TerminInhaltsart::META_BESCHREIBUNG, true )
		);
	}

	private function zeitpunkt( string $wert, \DateTimeZone $zone ): ?\DateTimeImmutable {
		if ( '' === $wert ) {
			return null;
		}
		$zeitpunkt = \DateTimeImmutable::createFromFormat( TerminInhaltsart::DB_FORMAT, $wert, $zone );
		return false === $zeitpunkt ? null : $zeitpunkt;
	}
}
