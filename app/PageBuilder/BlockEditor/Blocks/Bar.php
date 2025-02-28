<?php
/**
 * File to handle the block to show bar block.
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
class Bar extends Blocks_Base {

	/**
	 * Internal name of this block.
	 *
	 * @var string
	 */
	protected string $name = 'bar';

	/**
	 * Path to the directory where block.json resides.
	 *
	 * @var string
	 */
	protected string $path = 'blocks/bar/';

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
		'style' => array(
			'type'    => 'string',
			'default' => 'white',
		),
		'feedback' => array(
			'type'    => 'boolean',
			'default' => false,
		),
	);

	/**
	 * Get the content for this block.
	 *
	 * @param array $attributes List of attributes for this block.
	 * @return string
	 */
	public function render( array $attributes ): string {
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Bar::get_instance();
		$obj->set_style( $attributes['style'] );
		$obj->set_feedback( $attributes['feedback'] ? 1 : 0 );
		return $obj->get_html();
	}
}
