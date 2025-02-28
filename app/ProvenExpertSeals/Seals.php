<?php
/**
 * File to handle the list of supported ProvenExpert seals.
 *
 * @package provenexpert
 */

namespace ProvenExpert\ProvenExpertSeals;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Object which handles the list of available seals.
 */
class Seals {
	/**
	 * Instance of this object.
	 *
	 * @var ?Seals
	 */
	private static ?Seals $instance = null;

	/**
	 * Constructor for this object.
	 */
	private function __construct() {}

	/**
	 * Prevent cloning of this object.
	 *
	 * @return void
	 */
	private function __clone() {}

	/**
	 * Return the instance of this Singleton object.
	 */
	public static function get_instance(): Seals {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Return the list of ProvenExpert seals we support.
	 *
	 * @return array
	 */
	public function get_seals(): array {
		$seals = array(
			'ProvenExpert\ProvenExpertSeals\Seals\ProSeal',
		);

		/**
		 * Filter the possible seals.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param array $seals List of the seals.
		 */
		return apply_filters( 'provenexpert_provenexpert_seals', $seals );
	}

	/**
	 * Add a given md5 to the list of seals we have saved.
	 *
	 * @param string $md5 The md5 to add.
	 *
	 * @return void
	 */
	public function add_seal_with_code( string $md5 ): void {
		// get actual list.
		$list = get_option( 'provenExpertSeals' );

		// add md5 to this list.
		$list[] = $md5;

		// save the updated list.
		update_option( 'provenExpertSeals', $list );
	}

	/**
	 * Delete the complete seal cache (the html code of the seals).
	 *
	 * @return void
	 */
	public function delete_cache(): void {
		// delete each saved html code.
		foreach ( get_option( 'provenExpertSeals' ) as $md5 ) {
			delete_option( 'provenExpertSeal' . $md5 );
		}

		// empty the list.
		update_option( 'provenExpertSeals', array() );
	}
}
