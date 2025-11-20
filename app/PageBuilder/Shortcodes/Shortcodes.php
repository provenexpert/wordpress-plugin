<?php
/**
 * File to handle support for Shortcodes.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\Shortcodes;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\PageBuilder\PageBuilder_Base;

/**
 * Object to handle the shortcode support.
 */
class Shortcodes extends PageBuilder_Base {
	/**
	 * The internal name of this extension.
	 *
	 * @var string
	 */
	protected string $name = 'shortcodes';

	/**
	 * Instance of this object.
	 *
	 * @var ?Shortcodes
	 */
	private static ?Shortcodes $instance = null;

	/**
	 * Return the instance of this Singleton object.
	 */
	public static function get_instance(): Shortcodes {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize this PageBuilders support.
	 *
	 * @return void
	 */
	public function init(): void {
		// add our custom shortcodes.
		add_action( 'init', array( $this, 'register_shortcodes' ) );

		// call parent init.
		parent::init();
	}

	/**
	 * Return list of available shortcodes.
	 *
	 * @return array<int,string>
	 */
	public function get_widgets(): array {
		$list = array(
			'ProvenExpert\PageBuilder\Shortcodes\Shortcodes\Awards',
			'ProvenExpert\PageBuilder\Shortcodes\Shortcodes\Bar',
			'ProvenExpert\PageBuilder\Shortcodes\Shortcodes\Circle',
			'ProvenExpert\PageBuilder\Shortcodes\Shortcodes\Landing',
			'ProvenExpert\PageBuilder\Shortcodes\Shortcodes\ProSeal',
			'ProvenExpert\PageBuilder\Shortcodes\Shortcodes\Seal',
		);

		/**
		 * Return list of shortcode class names.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param array<int,string> $list List of shortcodes.
		 */
		return apply_filters( 'provenexpert_shortcodes', $list );
	}

	/**
	 * Add our custom blocks.
	 *
	 * @return void
	 */
	public function register_shortcodes(): void {
		foreach ( $this->get_widgets() as $shortcode_class_name ) {
			// get the object name.
			$obj_name = $shortcode_class_name . '::get_instance';

			// bail if the object is not callable.
			if ( ! is_callable( $obj_name ) ) {
				continue;
			}

			// get the object.
			$obj = $obj_name();

			// bail if the object could not be loaded.
			if ( ! $obj instanceof Shortcode_Base ) {
				continue;
			}

			// register this shortcode.
			$obj->register();
		}
	}
}
