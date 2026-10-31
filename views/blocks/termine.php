<?php
/**
 * View: Termine als Kacheln. Bekommt nur aufbereitete Daten in $daten.
 *
 * @package SchulBank\Core
 *
 * @var array<string, mixed> $daten
 */

defined( 'ABSPATH' ) || exit;

// Liefert escaptes HTML für einen Zeitpunkt, z. B. „Do / <span lang="sl">čet</span>, 12.11.2026, 18:00“.
$sbd_zeitpunkt = static function ( array $teil, string $zweitsprache ): string {
	$html = '';
	if ( '' !== $teil['tag'] ) {
		$html .= esc_html( $teil['tag'] );
		if ( '' !== $teil['tag_zweit'] ) {
			$html .= ' / <span lang="' . esc_attr( $zweitsprache ) . '">' . esc_html( $teil['tag_zweit'] ) . '</span>';
		}
		$html .= ', ' . esc_html( $teil['datum'] ) . ( '' !== $teil['zeit'] ? ', ' : '' );
	}
	return $html . esc_html( $teil['zeit'] );
};

// Beginn und gegebenenfalls Ende, ohne Leerraum um den Gedankenstrich („18:00–20:00“).
$sbd_zeitraum = static function ( array $termin, string $zweitsprache ) use ( $sbd_zeitpunkt ): string {
	$html = '<time datetime="' . esc_attr( $termin['iso_beginn'] ) . '">' . $sbd_zeitpunkt( $termin['beginn'], $zweitsprache ) . '</time>';
	if ( null !== $termin['ende'] ) {
		$html .= '' === $termin['ende']['datum'] ? '–' : ' bis ';
		$html .= '<time datetime="' . esc_attr( $termin['iso_ende'] ) . '">' . $sbd_zeitpunkt( $termin['ende'], $zweitsprache ) . '</time>';
	}
	return $html;
};
?>
<div <?php echo $daten['wrapper']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- von get_block_wrapper_attributes() escaped. ?>>
	<?php if ( array() === $daten['termine'] ) : ?>
		<p class="sbd-termine__leer"><?php echo esc_html( '' !== $daten['leertext'] ? $daten['leertext'] : 'Derzeit sind keine Termine eingetragen.' ); ?></p>
	<?php else : ?>
		<ul class="sbd-termine__liste" role="list">
			<?php foreach ( $daten['termine'] as $sbd_termin ) : ?>
				<li class="sbd-termin">
					<h3 class="sbd-termin__titel"><?php echo esc_html( $sbd_termin['titel'] ); ?></h3>
					<p class="sbd-termin__zeit"><?php echo $sbd_zeitraum( $sbd_termin, $daten['zweitsprache'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- im Closure escaped. ?></p>
					<?php if ( '' !== $sbd_termin['ort'] ) : ?>
						<p class="sbd-termin__ort"><?php echo esc_html( $sbd_termin['ort'] ); ?></p>
					<?php endif; ?>
					<?php if ( '' !== $sbd_termin['beschreibung'] ) : ?>
						<p class="sbd-termin__text"><?php echo nl2br( esc_html( $sbd_termin['beschreibung'] ) ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
