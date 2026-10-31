<?php
/**
 * Bereitet einen Termin für die Ausgabe auf: einheitlich „Do, 12.11.2026, 18:00“,
 * auf Wunsch mit slowenischem Wochentag („Do / čet, …“). Reines PHP, ohne WordPress testbar.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Service;

use SchulBank\Core\Model\Termin;

defined( 'ABSPATH' ) || exit;

final class TerminFormat {

	private const WOCHENTAGE = array(
		'de' => array( 'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa' ),
		'sl' => array( 'ned', 'pon', 'tor', 'sre', 'čet', 'pet', 'sob' ),
	);

	/**
	 * @param string $zweitsprache '' oder 'sl'.
	 * @return array{
	 *     iso_beginn: string,
	 *     beginn: array{tag: string, tag_zweit: string, datum: string, zeit: string},
	 *     ende: null|array{tag: string, tag_zweit: string, datum: string, zeit: string},
	 *     iso_ende: string,
	 *     ort: string,
	 *     beschreibung: string,
	 *     titel: string
	 * }
	 */
	public function fuer_ausgabe( Termin $termin, string $zweitsprache = '' ): array {
		$ende = null;
		if ( null !== $termin->ende ) {
			$ende = $this->teile( $termin->ende, $termin->ganztags, $zweitsprache );
			if ( $termin->ist_eintaegig() ) {
				// Gleicher Tag: nur die Uhrzeit wiederholen („18:00–20:00“).
				$ende['tag']       = '';
				$ende['tag_zweit'] = '';
				$ende['datum']     = '';
			}
			if ( '' === $ende['datum'] && '' === $ende['zeit'] ) {
				$ende = null;
			}
		}

		return array(
			'titel'        => $termin->titel,
			'iso_beginn'   => $this->iso( $termin->beginn, $termin->ganztags ),
			'beginn'       => $this->teile( $termin->beginn, $termin->ganztags, $zweitsprache ),
			'ende'         => $ende,
			'iso_ende'     => null === $termin->ende ? '' : $this->iso( $termin->ende, $termin->ganztags ),
			'ort'          => $termin->ort,
			'beschreibung' => $termin->beschreibung,
		);
	}

	/**
	 * @return array{tag: string, tag_zweit: string, datum: string, zeit: string}
	 */
	private function teile( \DateTimeImmutable $zeitpunkt, bool $ganztags, string $zweitsprache ): array {
		$wochentag = (int) $zeitpunkt->format( 'w' );

		return array(
			'tag'       => self::WOCHENTAGE['de'][ $wochentag ],
			'tag_zweit' => isset( self::WOCHENTAGE[ $zweitsprache ] ) && 'de' !== $zweitsprache ? self::WOCHENTAGE[ $zweitsprache ][ $wochentag ] : '',
			'datum'     => $zeitpunkt->format( 'd.m.Y' ),
			'zeit'      => $ganztags ? '' : $zeitpunkt->format( 'H:i' ),
		);
	}

	private function iso( \DateTimeImmutable $zeitpunkt, bool $ganztags ): string {
		return $ganztags ? $zeitpunkt->format( 'Y-m-d' ) : $zeitpunkt->format( 'Y-m-d\TH:i' );
	}
}
