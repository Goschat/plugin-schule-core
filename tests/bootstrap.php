<?php
/**
 * Bootstrap für die Unit-Tests: ohne WordPress, nur Autoloader.
 *
 * @package SchulBank\Core
 */

define( 'ABSPATH', __DIR__ . '/' );

require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';
require_once dirname( __DIR__ ) . '/src/Core/Autoloader.php';

SchulBank\Core\Core\Autoloader::registriere( dirname( __DIR__ ) . '/src' );
