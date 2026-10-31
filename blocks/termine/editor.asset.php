<?php
/**
 * Abhängigkeiten des Editor-Skripts (ohne Build-Schritt von Hand gepflegt).
 *
 * @package SchulBank\Core
 */

defined( 'ABSPATH' ) || exit;

return array(
	'dependencies' => array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render' ),
	'version'      => '0.1.0',
);
