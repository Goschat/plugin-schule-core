<?php
/**
 * Ein Termin als unveränderliches Objekt. Kennt weder WordPress noch HTML.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Model;

defined( 'ABSPATH' ) || exit;

final class Termin {

	public function __construct(
		public readonly int $id,
		public readonly string $titel,
		public readonly \DateTimeImmutable $beginn,
		public readonly bool $ganztags,
		public readonly ?\DateTimeImmutable $ende,
		public readonly string $ort,
		public readonly string $beschreibung
	) {}

	/**
	 * Endet am selben Kalendertag, an dem er beginnt (oder hat kein Ende).
	 */
	public function ist_eintaegig(): bool {
		return null === $this->ende || $this->ende->format( 'Y-m-d' ) === $this->beginn->format( 'Y-m-d' );
	}
}
