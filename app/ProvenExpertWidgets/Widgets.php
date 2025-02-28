<?php
/**
 * File to handle the list of supported ProvenExpert widgets.
 *
 * @package provenexpert
 */

namespace ProvenExpert\ProvenExpertWidgets;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Object which handles the list of available widgets.
 */
class Widgets {
	/**
	 * Instance of this object.
	 *
	 * @var ?Widgets
	 */
	private static ?Widgets $instance = null;

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
	public static function get_instance(): Widgets {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Return the list of ProvenExpert widgets we support.
	 *
	 * @return array
	 */
	public function get_widgets(): array {
		$widgets = array(
			'ProvenExpert\ProvenExpertWidgets\Widgets\Awards',
			'ProvenExpert\ProvenExpertWidgets\Widgets\Bar',
			'ProvenExpert\ProvenExpertWidgets\Widgets\Circle',
			'ProvenExpert\ProvenExpertWidgets\Widgets\Landing',
			'ProvenExpert\ProvenExpertWidgets\Widgets\Seal',
		);

		/**
		 * Filter the possible widgets.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param array $widgets List of the widgets.
		 */
		return apply_filters( 'provenexpert_provenexpert_widgets', $widgets );
	}

	/**
	 * Add a given md5 to the list of widgets we have saved.
	 *
	 * @param string $md5 The md5 to add.
	 *
	 * @return void
	 */
	public function add_widget_with_code( string $md5 ): void {
		// get actual list.
		$list = get_option( 'provenExpertWidgets' );

		// add md5 to this list.
		$list[] = $md5;

		// save the updated list.
		update_option( 'provenExpertWidgets', $list );
	}

	/**
	 * Delete the complete widget cache (the html code of the widgets).
	 *
	 * @return void
	 */
	public function delete_cache(): void {
		// delete each saved html code.
		foreach ( get_option( 'provenExpertWidgets' ) as $md5 ) {
			delete_option( 'provenExpertWidget' . $md5 );
		}

		// empty the list.
		update_option( 'provenExpertWidgets', array() );
	}
}
