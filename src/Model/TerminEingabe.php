<?php
/**
 * Prüft die Formulardaten eines Termins. Reines PHP, damit es ohne WordPress testbar ist.
 * Die Bereinigung der Zeichenketten (sanitize_*) erledigt vorher der Controller.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Model;

defined( 'ABSPATH' ) || exit;

final class TerminEingabe {

	public const MAX_ORT          = 120;
	public const MAX_BESCHREIBUNG = 600;

	/**
	 * @param string[] $fehler
	 */
	private function __construct(
		public readonly ?\DateTimeImmutable $beginn,
		public readonly bool $ganztags,
		public readonly ?\DateTimeImmutable $ende,
		public readonly string $ort,
		public readonly string $beschreibung,
		public readonly array $fehler
	) {}

	/**
	 * @param array<string, string> $roh Schlüssel: beginn_datum, beginn_zeit, ende_datum, ende_zeit, ort, beschreibung.
	 */
	public static function aus_formular( array $roh, \DateTimeZone $zone ): self {
		$fehler = array();

		$beginn_zeit = trim( $roh['beginn_zeit'] ?? '' );
		$ganztags    = '' === $beginn_zeit;
		$beginn      = self::zeitpunkt( $roh['beginn_datum'] ?? '', $beginn_zeit, $zone );
		if ( null === $beginn ) {
			$fehler[] = 'Bitte ein gültiges Datum für den Beginn angeben.';
		}

		$ende       = null;
		$ende_datum = trim( $roh['ende_datum'] ?? '' );
		$ende_zeit  = trim( $roh['ende_zeit'] ?? '' );
		if ( '' !== $ende_datum || '' !== $ende_zeit ) {
			// Nur eine Endzeit angegeben: gleicher Tag wie der Beginn.
			if ( '' === $ende_datum && null !== $beginn ) {
				$ende_datum = $beginn->format( 'Y-m-d' );
			}
			$ende = self::zeitpunkt( $ende_datum, $ende_zeit, $zone );
			if ( null === $ende ) {
				$fehler[] = 'Das Ende ist kein gültiges Datum.';
			} elseif ( null !== $beginn && $ende < $beginn ) {
				$fehler[] = 'Das Ende liegt vor dem Beginn.';
				$ende     = null;
			}
		}

		$ort = trim( $roh['ort'] ?? '' );
		if ( mb_strlen( $ort ) > self::MAX_ORT ) {
			$fehler[] = sprintf( 'Der Ort ist zu lang (höchstens %d Zeichen).', self::MAX_ORT );
			$ort      = mb_substr( $ort, 0, self::MAX_ORT );
		}

		$beschreibung = trim( $roh['beschreibung'] ?? '' );
		if ( mb_strlen( $beschreibung ) > self::MAX_BESCHREIBUNG ) {
			$fehler[]     = sprintf( 'Die Beschreibung ist zu lang (höchstens %d Zeichen).', self::MAX_BESCHREIBUNG );
			$beschreibung = mb_substr( $beschreibung, 0, self::MAX_BESCHREIBUNG );
		}

		return new self( $beginn, $ganztags, $ende, $ort, $beschreibung, $fehler );
	}

	public function ist_gueltig(): bool {
		return array() === $this->fehler;
	}

	private static function zeitpunkt( string $datum, string $zeit, \DateTimeZone $zone ): ?\DateTimeImmutable {
		$datum = trim( $datum );
		$zeit  = '' === trim( $zeit ) ? '00:00' : trim( $zeit );
		if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $datum ) || ! preg_match( '/^\d{2}:\d{2}$/', $zeit ) ) {
			return null;
		}
		$wert = \DateTimeImmutable::createFromFormat( '!Y-m-d H:i', $datum . ' ' . $zeit, $zone );
		if ( false === $wert || $wert->format( 'Y-m-d H:i' ) !== $datum . ' ' . $zeit ) {
			return null; // z. B. 31.02. oder 25:00
		}
		return $wert;
	}
}
