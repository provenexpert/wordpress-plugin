<?php
/**
 * File to validate the given timeout.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Admin\SettingsValidation;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Object which validates the timeout given.
 */
class UrlTimeout {
	/**
	 * Validate the usage of languages.
	 *
	 * @param string|null $value Value of setting.
	 *
	 * @return int
	 */
	public static function validate( string|null $value ): int {
		$value = absint( $value );
		if ( 0 === $value ) {
			add_settings_error( 'provenExpertUrlTimeout', 'provenExpertUrlTimeout', __( 'A timeout must have a value greater than 0.', 'provenexpert' ) );
		}
		return $value;
	}
}
