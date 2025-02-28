<?php
/**
 * File to handle the block to show proseal seal.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\BlockEditor\Blocks;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\PageBuilder\BlockEditor\Blocks_Base;

/**
 * Object to handle this block.
 */
class ProSeal extends Blocks_Base {

	/**
	 * Internal name of this block.
	 *
	 * @var string
	 */
	protected string $name = 'proseal';

	/**
	 * Path to the directory where block.json resides.
	 *
	 * @var string
	 */
	protected string $path = 'blocks/proseal/';

	/**
	 * Attributes this block is using.
	 *
	 * @var array
	 */
	protected array $attributes = array(
		'preview' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'blockId' => array(
			'type'    => 'string',
			'default' => '',
		),
		'bannercolor' => array(
			'type'    => 'string',
			'default' => '#000000',
		),
		'textcolor' => array(
			'type'    => 'string',
			'default' => '#ffffff',
		),
		'showbackpage' => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'showreviews' => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'hidedate' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'hidename' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'googlestars' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'displayreviewerlastname' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'bottom' => array(
			'type'    => 'integer',
			'default' => 130,
		),
		'stickytoside' => array(
			'type'    => 'string',
			'default' => 'right',
		),
		'zindex' => array(
			'type'    => 'integer',
			'default' => 9999,
		),
	);

	/**
	 * Get the content for this block.
	 *
	 * @param array $attributes List of attributes for this block.
	 * @return string
	 */
	public function render( array $attributes ): string {
		// get the object.
		$obj = \ProvenExpert\ProvenExpertSeals\Seals\ProSeal::get_instance();

		// set the attributes.
		$obj->set_banner_color( $attributes['bannercolor'] );
		$obj->set_text_color( $attributes['textcolor'] );
		$obj->set_show_back_page( 1 === absint( $attributes['showbackpage'] ) );
		$obj->set_show_reviews( 1 === absint( $attributes['showreviews'] ) );
		$obj->set_hide_date( 1 === absint( $attributes['hidedate'] ) );
		$obj->set_hide_name( 1 === absint( $attributes['hidename'] ) );
		$obj->set_google_stars( 1 === absint( $attributes['googlestars'] ) );
		$obj->set_display_reviewer_last_name( 1 === absint( $attributes['displayreviewerlastname'] ) );
		$obj->set_bottom( absint( $attributes['bottom'] ) );
		$obj->set_sticky_to_side( $attributes['stickytoside'] );
		$obj->set_z_index( absint( $attributes['zindex'] ) );

		// display the resulting HTML-code from object.
		return $obj->get_html();
	}
}
