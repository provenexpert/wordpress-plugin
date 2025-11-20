<?php
/**
 * File to handle the block to show landing block.
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
class Landing extends Blocks_Base {

	/**
	 * Internal name of this block.
	 *
	 * @var string
	 */
	protected string $name = 'landing';

	/**
	 * Path to the directory where block.json resides.
	 *
	 * @var string
	 */
	protected string $path = 'blocks/landing/';

	/**
	 * Attributes this block is using.
	 *
	 * @var array<string,mixed>
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
			'default' => true,
		),
		'avatar' => array(
			'type'    => 'boolean',
			'default' => true,
		),
		'competence' => array(
			'type'    => 'boolean',
			'default' => true
		),
	);

	/**
	 * Instance of this object.
	 *
	 * @var ?Landing
	 */
	private static ?Landing $instance = null;

	/**
	 * Return the instance of this Singleton object.
	 */
	public static function get_instance(): Landing {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get the content for this block.
	 *
	 * @param array<string,mixed> $attributes List of attributes for this block.
	 * @return string
	 */
	public function render( array $attributes ): string {
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Landing::get_instance();
		$obj->set_style( $attributes['style'] );
		$obj->set_feedback( $attributes['feedback'] ? 1 : 0 );
		$obj->set_avatar( $attributes['avatar'] );
		$obj->set_competence( $attributes['competence'] );
		return $obj->get_html();
	}
}
