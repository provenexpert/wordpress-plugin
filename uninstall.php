<?php
/**
 * Tasks to run during uninstallation of this plugin.
 *
 * @package provenexpert
 */

namespace ProvenExpert;

use ProvenExpert\Plugin\Uninstaller;

// if uninstall.php is not called by WordPress, die.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// do nothing if PHP-version is not 8.0 or newer.
if ( version_compare( PHP_VERSION, '8.0', '<' ) ) {
	return;
}

// set version number.
define( 'PROVENEXPERT_VERSION', '@@VersionNumber@@' );

// save plugin-path.
define( 'PROVENEXPERT_PLUGIN', __FILE__ );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// include necessary files.
require __DIR__ . '/inc/constants.php';

// run uninstaller.
Uninstaller::get_instance()->run();
