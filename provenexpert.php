<?php
/**
 * Plugin Name:       ProvenExpert
 * Description:       Add widgets from ProvenExpert in your website.
 * Requires at least: 4.9.25
 * Requires PHP:      8.0
 * Version:           @@VersionNumber@@
 * Author:            ProvenExpert
 * Author URI:        https://www.provenexpert.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       provenexpert
 *
 * @package provenexpert
 */

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Plugin\Init;
use ProvenExpert\Plugin\Update;

// do nothing if PHP-version is not 8.0 or newer.
if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
	return;
}

// set version number.
define( 'PROVENEXPERT_VERSION', '@@VersionNumber@@' );

// save plugin-path.
define( 'PROVENEXPERT_PLUGIN', __FILE__ );

// get autoloader generated by composer.
require_once __DIR__ . '/vendor/autoload.php';

// get constants.
require_once __DIR__ . '/inc/constants.php';

// on activation.
register_activation_hook( PROVENEXPERT_PLUGIN, array( Init::get_instance(), 'activation' ) );

// on deactivation.
register_deactivation_hook( PROVENEXPERT_PLUGIN, array( Init::get_instance(), 'deactivation' ) );

// initialize the plugin.
add_action(
	'plugins_loaded',
	function () {
		Update::get_instance()->run();
		Init::get_instance()->init();
	}
);
