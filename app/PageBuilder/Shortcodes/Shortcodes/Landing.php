<?php
/**
 * File to handle the shortcode to show landing widget.
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
class Landing extends Shortcode_Base {

	/**
	 * Internal name of this shortcode.
	 *
	 * @var string
	 */
	protected string $name = 'landing';

	/**
	 * Get the content for this widget.
	 *
	 * @param array $attributes List of attributes for this widget.
	 * @return string
	 */
	public function render( array $attributes ): string {
		// get the object.
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Landing::get_instance();

		// set the attributes, if given.
		if ( isset( $attributes['style'] ) ) {
			$obj->set_style( $attributes['style'] );
		}
		if ( isset( $attributes['feedback'] ) ) {
			$obj->set_feedback( $attributes['feedback'] ? 1 : 0 );
		}
		if ( isset( $attributes['avatar'] ) ) {
			$obj->set_avatar( $attributes['avatar'] );
		}
		if ( isset( $attributes['competence'] ) ) {
			$obj->set_competence( $attributes['competence'] );
		}

		// return the resulting HTML-code from object.
		return $obj->get_html();
	}
}
