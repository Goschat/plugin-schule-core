<?php
/**
 * Updates für Plugin und Basis-Theme aus den öffentlichen GitHub-Repos.
 * Der Plugin Update Checker fragt höchstens alle 12 Stunden bei GitHub an (serverseitig,
 * nie aus dem Browser der Besucher). Ein Update ist ein Git-Tag wie v0.2.0.
 *
 * @package SchulBank\Core
 */

namespace SchulBank\Core\Controller\Updates;

use SchulBank\Core\Core\Controller;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

defined( 'ABSPATH' ) || exit;

final class Aktualisierung implements Controller {

	public const REPO_PLUGIN = 'https://github.com/Goschat/plugin-schule-core/';
	public const REPO_THEME  = 'https://github.com/Goschat/theme-schule-basis/';
	public const THEME_SLUG  = 'theme-schule-basis';

	public function __construct( private string $plugin_datei ) {}

	public function registriere(): void {
		require_once dirname( $this->plugin_datei ) . '/lib/plugin-update-checker/plugin-update-checker.php';

		// Zweig "main": der Checker nimmt dann das neueste Release bzw. den höchsten Versions-Tag.
		PucFactory::buildUpdateChecker( self::REPO_PLUGIN, $this->plugin_datei, 'plugin-schule-core' )->setBranch( 'main' );

		$theme_verzeichnis = get_theme_root( self::THEME_SLUG ) . '/' . self::THEME_SLUG;
		if ( is_readable( $theme_verzeichnis . '/style.css' ) ) {
			PucFactory::buildUpdateChecker( self::REPO_THEME, $theme_verzeichnis, self::THEME_SLUG )->setBranch( 'main' );
		}
	}
}
