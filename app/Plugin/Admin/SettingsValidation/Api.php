<?php
/**
 * File to validate the API-values.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Admin\SettingsValidation;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Object which validates the timeout given.
 */
class Api {
	/**
	 * Validate the usage of languages.
	 *
	 * @param string|null $value Value of setting.
	 *
	 * @return string
	 */
	public static function validate( string|null $value ): string {
		if ( is_null( $value ) ) {
			return '';
		}
		return trim( $value );
	}
}
