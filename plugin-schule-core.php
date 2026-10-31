<?php
/**
 * Plugin Name:       Schule Core
 * Description:       Funktionen, Härtung und Inhaltsarten für die Schul-Websites (Baukasten SchulBankDruecken).
 * Version:           0.1.0
 * Requires at least: 6.6
 * Requires PHP:      8.1
 * Author:            Thomas F. Piber
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       schule-core
 * Domain Path:       /languages
 *
 * @package SchulBank\Core
 */

defined( 'ABSPATH' ) || exit;

// Einstieg: nur Autoloader und Start. Hooks registrieren ausschließlich die Controller.
require_once __DIR__ . '/src/Core/Autoloader.php';

SchulBank\Core\Core\Autoloader::registriere( __DIR__ . '/src' );
SchulBank\Core\Core\Plugin::starte( __FILE__ );
