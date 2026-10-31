<?php
/**
 * Definition der Inhaltsart „Termin“ und ihrer Felder. Registriert wird sie vom Controller.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Model;

defined( 'ABSPATH' ) || exit;

final class TerminInhaltsart {

	public const TYP = 'sbd_termin';

	public const META_BEGINN       = '_sbd_beginn';
	public const META_GANZTAGS     = '_sbd_ganztags';
	public const META_ENDE         = '_sbd_ende';
	public const META_ORT          = '_sbd_ort';
	public const META_BESCHREIBUNG = '_sbd_beschreibung';

	/** Format in der Datenbank: als Zeichenkette sortierbar, in der Zeitzone der Website. */
	public const DB_FORMAT = 'Y-m-d H:i:s';

	/**
	 * Argumente für register_post_type(). Termine haben keine eigenen Seiten,
	 * sie erscheinen nur als Kacheln im Block „Nächste Termine“.
	 *
	 * @return array<string, mixed>
	 */
	public static function argumente(): array {
		return array(
			'labels'              => array(
				'name'               => 'Termine',
				'singular_name'      => 'Termin',
				'menu_name'          => 'Termine',
				'add_new'            => 'Neuer Termin',
				'add_new_item'       => 'Neuen Termin anlegen',
				'edit_item'          => 'Termin bearbeiten',
				'new_item'           => 'Neuer Termin',
				'search_items'       => 'Termine durchsuchen',
				'not_found'          => 'Keine Termine gefunden.',
				'not_found_in_trash' => 'Keine Termine im Papierkorb.',
				'all_items'          => 'Alle Termine',
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'menu_position'       => 6,
			'menu_icon'           => 'dashicons-calendar-alt',
			'supports'            => array( 'title' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
		);
	}
}
