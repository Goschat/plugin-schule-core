<?php

namespace SchulBank\Core\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SchulBank\Core\Model\Termin;
use SchulBank\Core\Service\TerminFormat;

final class TerminFormatTest extends TestCase {

	private function termin( string $beginn, ?string $ende = null, bool $ganztags = false ): Termin {
		$zone = new \DateTimeZone( 'Europe/Vienna' );
		return new Termin(
			7,
			'Elternabend',
			new \DateTimeImmutable( $beginn, $zone ),
			$ganztags,
			null === $ende ? null : new \DateTimeImmutable( $ende, $zone ),
			'Turnsaal',
			''
		);
	}

	public function test_einheitliches_format_deutsch(): void {
		$daten = ( new TerminFormat() )->fuer_ausgabe( $this->termin( '2026-11-12 18:00' ) );

		$this->assertSame( 'Do', $daten['beginn']['tag'] );
		$this->assertSame( '', $daten['beginn']['tag_zweit'] );
		$this->assertSame( '12.11.2026', $daten['beginn']['datum'] );
		$this->assertSame( '18:00', $daten['beginn']['zeit'] );
		$this->assertSame( '2026-11-12T18:00', $daten['iso_beginn'] );
		$this->assertNull( $daten['ende'] );
	}

	public function test_slowenischer_wochentag(): void {
		$format = new TerminFormat();

		$tage = array();
		foreach ( array( '2026-11-08', '2026-11-09', '2026-11-10', '2026-11-11', '2026-11-12', '2026-11-13', '2026-11-14' ) as $datum ) {
			$tage[] = $format->fuer_ausgabe( $this->termin( $datum . ' 10:00' ), 'sl' )['beginn']['tag_zweit'];
		}

		$this->assertSame( array( 'ned', 'pon', 'tor', 'sre', 'čet', 'pet', 'sob' ), $tage );
	}

	public function test_gleicher_tag_zeigt_nur_endzeit(): void {
		$daten = ( new TerminFormat() )->fuer_ausgabe( $this->termin( '2026-11-12 18:00', '2026-11-12 20:00' ) );

		$this->assertSame( '', $daten['ende']['datum'] );
		$this->assertSame( '20:00', $daten['ende']['zeit'] );
	}

	public function test_mehrtaegig_ganztags(): void {
		$daten = ( new TerminFormat() )->fuer_ausgabe( $this->termin( '2026-12-23 00:00', '2027-01-06 00:00', true ), 'sl' );

		$this->assertSame( '', $daten['beginn']['zeit'] );
		$this->assertSame( '2026-12-23', $daten['iso_beginn'] );
		$this->assertSame( 'Mi', $daten['ende']['tag'] );
		$this->assertSame( 'sre', $daten['ende']['tag_zweit'] );
		$this->assertSame( '06.01.2027', $daten['ende']['datum'] );
	}

	public function test_ganztags_am_selben_tag_hat_kein_ende(): void {
		$daten = ( new TerminFormat() )->fuer_ausgabe( $this->termin( '2026-12-23 00:00', '2026-12-23 00:00', true ) );

		$this->assertNull( $daten['ende'] );
	}

	public function test_unbekannte_zweitsprache_wird_ignoriert(): void {
		$daten = ( new TerminFormat() )->fuer_ausgabe( $this->termin( '2026-11-12 18:00' ), 'xx' );

		$this->assertSame( '', $daten['beginn']['tag_zweit'] );
	}
}
