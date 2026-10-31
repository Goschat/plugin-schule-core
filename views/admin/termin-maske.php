<?php
/**
 * View: Eingabemaske eines Termins. Native Datums- und Zeitfelder, kein eigenes JavaScript.
 *
 * @package SchulBank\Core
 *
 * @var array<string, mixed> $daten
 */

defined( 'ABSPATH' ) || exit;

wp_nonce_field( $daten['nonce_aktion'], $daten['nonce_feld'] );
?>
<fieldset class="sbd-maske">
	<legend class="screen-reader-text">Beginn</legend>
	<p>
		<label for="sbd_beginn_datum"><strong>Beginn: Datum</strong> (Pflicht)</label><br>
		<input type="date" id="sbd_beginn_datum" name="sbd_beginn_datum" required value="<?php echo esc_attr( $daten['beginn_datum'] ); ?>">
	</p>
	<p>
		<label for="sbd_beginn_zeit"><strong>Beginn: Uhrzeit</strong></label><br>
		<input type="time" id="sbd_beginn_zeit" name="sbd_beginn_zeit" aria-describedby="sbd_zeit_hilfe" value="<?php echo esc_attr( $daten['beginn_zeit'] ); ?>">
		<br><span id="sbd_zeit_hilfe" class="description">Leer lassen, wenn der Termin den ganzen Tag dauert.</span>
	</p>
</fieldset>
<fieldset class="sbd-maske">
	<legend class="screen-reader-text">Ende</legend>
	<p>
		<label for="sbd_ende_datum"><strong>Ende: Datum</strong></label><br>
		<input type="date" id="sbd_ende_datum" name="sbd_ende_datum" aria-describedby="sbd_ende_hilfe" value="<?php echo esc_attr( $daten['ende_datum'] ); ?>">
	</p>
	<p>
		<label for="sbd_ende_zeit"><strong>Ende: Uhrzeit</strong></label><br>
		<input type="time" id="sbd_ende_zeit" name="sbd_ende_zeit" aria-describedby="sbd_ende_hilfe" value="<?php echo esc_attr( $daten['ende_zeit'] ); ?>">
		<br><span id="sbd_ende_hilfe" class="description">Optional. Nur die Uhrzeit angeben, wenn der Termin am selben Tag endet.</span>
	</p>
</fieldset>
<p>
	<label for="sbd_ort"><strong>Ort</strong></label><br>
	<input type="text" class="widefat" id="sbd_ort" name="sbd_ort" maxlength="<?php echo (int) $daten['max_ort']; ?>" value="<?php echo esc_attr( $daten['ort'] ); ?>">
</p>
<p>
	<label for="sbd_beschreibung"><strong>Kurze Beschreibung</strong></label><br>
	<textarea class="widefat" id="sbd_beschreibung" name="sbd_beschreibung" rows="4" maxlength="<?php echo (int) $daten['max_text']; ?>"><?php echo esc_textarea( $daten['beschreibung'] ); ?></textarea>
</p>
