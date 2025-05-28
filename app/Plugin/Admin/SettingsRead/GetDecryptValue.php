<?php
/**
 * File to get a given value as decrypted value.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Admin\SettingsRead;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Plugin\Crypt;

/**
 * Object which get a given value as decrypted value.
 */
class GetDecryptValue {
	/**
	 * Generate the crypt value.
	 *
	 * @param string|null $value The value to return decrypted.
	 *
	 * @return string|null
	 */
	public static function get( string|null $value ): null|string {
		// bail if value is empty.
		if ( empty( $value ) ) {
			return '';
		}

		$false = false;
		/**
		 * Do not decrypt a given value if requested.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 *
		 * @param bool $false Return true to prevent decrypting.
		 * @param string $value The requested value.
		 *
		 * @noinspection PhpConditionAlreadyCheckedInspection
		 */
		if ( apply_filters( 'provenexpert_do_not_decrypt', $false, $value ) ) {
			return $value;
		}

		// return the new value to save it via WP.
		return Crypt::get_instance()->decrypt( $value );
	}
}
