<?php
/**
 * Encrypt new API ID settings.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Admin\SettingsSave;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Plugin\Crypt;

/**
 * Object which encrypt the API ID settings.
 */
class ApiId {
	/**
	 * Save the new setting.
	 *
	 * @param string $value The value to save.
	 *
	 * @return string
	 */
	public static function save( string $value ): string {
		if ( empty( $value ) ) {
			return $value;
		}

		// encrypt the value.
		return Crypt::get_instance()->encrypt( $value );
	}
}
