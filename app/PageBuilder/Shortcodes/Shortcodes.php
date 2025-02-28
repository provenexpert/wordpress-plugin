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
	 * @return array
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
		 * @param array $list List of shortcodes.
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
			$obj = call_user_func( $shortcode_class_name . '::get_instance' );
			if ( $obj instanceof Shortcode_Base ) {
				$obj->register();
			}
		}
	}
}
