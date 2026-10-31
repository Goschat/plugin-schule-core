<?php

namespace SchulBank\Core\Tests\Unit;

use PHPUnit\Framework\TestCase;
use SchulBank\Core\Model\TerminEingabe;

final class TerminEingabeTest extends TestCase {

	private \DateTimeZone $zone;

	protected function setUp(): void {
		$this->zone = new \DateTimeZone( 'Europe/Vienna' );
	}

	public function test_termin_mit_uhrzeit_ist_gueltig(): void {
		$eingabe = TerminEingabe::aus_formular(
			array(
				'beginn_datum' => '2026-11-12',
				'beginn_zeit'  => '18:00',
				'ort'          => 'Turnsaal',
			),
			$this->zone
		);

		$this->assertTrue( $eingabe->ist_gueltig() );
		$this->assertFalse( $eingabe->ganztags );
		$this->assertSame( '2026-11-12 18:00', $eingabe->beginn->format( 'Y-m-d H:i' ) );
		$this->assertNull( $eingabe->ende );
		$this->assertSame( 'Turnsaal', $eingabe->ort );
	}

	public function test_ohne_uhrzeit_ist_ganztags(): void {
		$eingabe = TerminEingabe::aus_formular( array( 'beginn_datum' => '2026-12-23' ), $this->zone );

		$this->assertTrue( $eingabe->ist_gueltig() );
		$this->assertTrue( $eingabe->ganztags );
	}

	public function test_beginn_ist_pflicht(): void {
		$eingabe = TerminEingabe::aus_formular( array( 'beginn_zeit' => '18:00' ), $this->zone );

		$this->assertFalse( $eingabe->ist_gueltig() );
		$this->assertNull( $eingabe->beginn );
	}

	public function test_ungueltiges_datum_wird_abgelehnt(): void {
		$eingabe = TerminEingabe::aus_formular( array( 'beginn_datum' => '2026-02-31' ), $this->zone );

		$this->assertFalse( $eingabe->ist_gueltig() );
	}

	public function test_nur_endzeit_bedeutet_gleicher_tag(): void {
		$eingabe = TerminEingabe::aus_formular(
			array(
				'beginn_datum' => '2026-11-12',
				'beginn_zeit'  => '18:00',
				'ende_zeit'    => '20:00',
			),
			$this->zone
		);

		$this->assertTrue( $eingabe->ist_gueltig() );
		$this->assertSame( '2026-11-12 20:00', $eingabe->ende->format( 'Y-m-d H:i' ) );
	}

	public function test_ende_vor_beginn_wird_abgelehnt(): void {
		$eingabe = TerminEingabe::aus_formular(
			array(
				'beginn_datum' => '2026-11-12',
				'beginn_zeit'  => '18:00',
				'ende_zeit'    => '17:00',
			),
			$this->zone
		);

		$this->assertFalse( $eingabe->ist_gueltig() );
		$this->assertNull( $eingabe->ende );
	}

	public function test_zu_langer_ort_wird_gekuerzt(): void {
		$eingabe = TerminEingabe::aus_formular(
			array(
				'beginn_datum' => '2026-11-12',
				'ort'          => str_repeat( 'ä', TerminEingabe::MAX_ORT + 5 ),
			),
			$this->zone
		);

		$this->assertFalse( $eingabe->ist_gueltig() );
		$this->assertSame( TerminEingabe::MAX_ORT, mb_strlen( $eingabe->ort ) );
	}
}
