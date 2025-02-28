<?php
/**
 * File to handle the shortcode to show proseal seal.
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
class ProSeal extends Shortcode_Base {

	/**
	 * Internal name of this shortcode.
	 *
	 * @var string
	 */
	protected string $name = 'proseal';

	/**
	 * Get the content for this widget.
	 *
	 * Example: [provenexpert_proseal bannerColor="#ffffff" textcolor="#000000" showbackpage="1" showreviews="1" hidedate="1" hidename="1" googlestars="1" displayreviewerlastName="1" bottom="42" stickytoside="right" zindex="84"]
	 *
	 * @param array $attributes List of attributes for this widget.
	 * @return string
	 */
	public function render( array $attributes ): string {
		// get the object.
		$obj = \ProvenExpert\ProvenExpertSeals\Seals\ProSeal::get_instance();

		// set the attributes, if given.
		if ( isset( $attributes['bannercolor'] ) ) {
			$obj->set_banner_color( $attributes['bannercolor'] );
		}
		if ( isset( $attributes['textcolor'] ) ) {
			$obj->set_text_color( $attributes['textcolor'] );
		}
		if ( isset( $attributes['showbackpage'] ) ) {
			$obj->set_show_back_page( 1 === absint( $attributes['showbackpage'] ) );
		}
		if ( isset( $attributes['showreviews'] ) ) {
			$obj->set_show_reviews( 1 === absint( $attributes['showreviews'] ) );
		}
		if ( isset( $attributes['hidedate'] ) ) {
			$obj->set_hide_date( 1 === absint( $attributes['hidedate'] ) );
		}
		if ( isset( $attributes['hidename'] ) ) {
			$obj->set_hide_name( 1 === absint( $attributes['hidename'] ) );
		}
		if ( isset( $attributes['googlestars'] ) ) {
			$obj->set_google_stars( 1 === absint( $attributes['googlestars'] ) );
		}
		if ( isset( $attributes['displayreviewerlastname'] ) ) {
			$obj->set_display_reviewer_last_name( 1 === absint( $attributes['displayreviewerlastname'] ) );
		}
		if ( isset( $attributes['bottom'] ) ) {
			$obj->set_bottom( absint( $attributes['bottom'] ) );
		}
		if ( isset( $attributes['stickytoside'] ) ) {
			$obj->set_sticky_to_side( $attributes['stickytoside'] );
		}
		if ( isset( $attributes['zindex'] ) ) {
			$obj->set_z_index( absint( $attributes['zindex'] ) );
		}

		// return the resulting HTML-code from object.
		return $obj->get_html();
	}
}
