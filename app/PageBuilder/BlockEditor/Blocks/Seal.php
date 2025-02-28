<?php
/**
 * File to handle the block to show seal block.
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
class Seal extends Blocks_Base {

	/**
	 * Internal name of this block.
	 *
	 * @var string
	 */
	protected string $name = 'seal';

	/**
	 * Path to the directory where block.json resides.
	 *
	 * @var string
	 */
	protected string $path = 'blocks/seal/';

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
		'width' => array(
			'type'    => 'integer',
			'default' => 250,
		),
		'feedback' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'slider' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'fixed' => array(
			'type'    => 'boolean',
			'default' => false,
		),
		'origin' => array(
			'type'    => 'string',
			'default' => 'bottom',
		),
		'position' => array(
			'type'    => 'integer',
			'default' => 0,
		),
		'side' => array(
			'type'    => 'string',
			'default' => 'right',
		),
		'seal_type' => array(
			'type'    => 'string',
			'default' => 'portrait',
		),
	);

	/**
	 * Get the content for this block.
	 *
	 * @param array $attributes List of attributes for this block.
	 * @return string
	 */
	public function render( array $attributes ): string {
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Seal::get_instance();
		$obj->set_width( $attributes['width'] );
		$obj->set_fixed( $attributes['fixed'] ? 1 : 0 );
		$obj->set_origin( $attributes['origin'] );
		$obj->set_position( $attributes['position'] );
		$obj->set_side( $attributes['side'] );
		$obj->set_seal_type( $attributes['seal_type'] );
		$obj->set_slider( $attributes['slider'] ? 1 : 0 );
		return $obj->get_html();
	}
}
