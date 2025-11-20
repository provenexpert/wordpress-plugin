<?php
/**
 * File to handle the shortcode to show bar widget.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\Shortcodes\Shortcodes;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\PageBuilder\Shortcodes\Shortcode_Base;

/**
 * Object to handle this shortcode.
 */
class Bar extends Shortcode_Base {

	/**
	 * Internal name of this shortcode.
	 *
	 * @var string
	 */
	protected string $name = 'bar';

	/**
	 * Instance of this object.
	 *
	 * @var ?Bar
	 */
	private static ?Bar $instance = null;

	/**
	 * Return the instance of this Singleton object.
	 */
	public static function get_instance(): Bar {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get the content for this widget.
	 *
	 * @param array<string,mixed> $attributes List of attributes for this widget.
	 * @return string
	 */
	public function render( array $attributes ): string {
		// get the object.
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Bar::get_instance();

		// set the attributes, if given.
		if ( isset( $attributes['style'] ) ) {
			$obj->set_style( $attributes['style'] );
		}
		if ( isset( $attributes['feedback'] ) ) {
			$obj->set_feedback( $attributes['feedback'] ? 1 : 0 );
		}

		// return the resulting HTML-code from object.
		return $obj->get_html();
	}
}
