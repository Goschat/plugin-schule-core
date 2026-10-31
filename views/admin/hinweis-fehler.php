<?php
/**
 * View: Fehlermeldungen nach dem Speichern eines Termins.
 *
 * @package SchulBank\Core
 *
 * @var array<string, mixed> $daten
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="notice notice-error" role="alert">
	<p><strong>Der Termin wurde nicht vollständig gespeichert:</strong></p>
	<ul>
		<?php foreach ( $daten['fehler'] as $sbd_fehler ) : ?>
			<li><?php echo esc_html( $sbd_fehler ); ?></li>
		<?php endforeach; ?>
	</ul>
</div>
