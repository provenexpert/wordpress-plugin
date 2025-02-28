<?php
/**
 * File to handle the shortcode to show awards widget.
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
class Awards extends Shortcode_Base {
	/**
	 * Internal name of this shortcode.
	 *
	 * @var string
	 */
	protected string $name = 'awards';

	/**
	 * Get the content for this widget.
	 *
	 * @param array $attributes List of attributes for this widget.
	 * @return string
	 */
	public function render( array $attributes ): string {
		// get the object.
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Awards::get_instance();

		// set the attributes, if given.
		if ( isset( $attributes['width'] ) ) {
			$obj->set_width( $attributes['width'] );
		}
		if ( isset( $attributes['fixed'] ) ) {
			$obj->set_fixed( $attributes['fixed'] ? 1 : 0 );
		}
		if ( isset( $attributes['origin'] ) ) {
			$obj->set_origin( $attributes['origin'] );
		}
		if ( isset( $attributes['position'] ) ) {
			$obj->set_position( $attributes['position'] );
		}
		if ( isset( $attributes['award_type'] ) ) {
			$obj->set_award_type( $attributes['award_type'] );
		}

		// return the resulting HTML-code from object.
		return $obj->get_html();
	}
}
