<?php
/**
 * Entfernt beim Löschen des Plugins alles, was es angelegt hat: Termine samt Meta-Daten und Optionen.
 *
 * @package SchulBank\Core
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

$sbd_termine = get_posts(
	array(
		'post_type'      => 'sbd_termin',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	)
);
foreach ( $sbd_termine as $sbd_id ) {
	wp_delete_post( $sbd_id, true );
}

delete_option( 'sbd_module_aus' );
delete_option( 'sbd_zweitsprache' );

// Zwischenspeicher des Plugin Update Checkers.
delete_site_option( 'external_updates-plugin-schule-core' );
delete_site_option( 'external_updates-theme-schule-basis' );
