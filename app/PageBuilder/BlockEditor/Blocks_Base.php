<?php
/**
 * File to handle main functions for single block.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\BlockEditor;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Api\Api;
use ProvenExpert\Plugin\Helper;
use WP_Block_Type_Registry;

/**
 * Object to handle main functions for single block.
 */
class Blocks_Base {
	/**
	 * Internal name of this block.
	 *
	 * @var string
	 */
	protected string $name = '';

	/**
	 * Path to the directory where block.json resides.
	 *
	 * @var string
	 */
	protected string $path = '';

	/**
	 * Attributes this block is using.
	 *
	 * @var array<string,mixed>
	 */
	protected array $attributes = array();

	/**
	 * The instance of this object.
	 *
	 * @var Blocks_Base|null
	 */
	private static ?Blocks_Base $instance = null;

	/**
	 * Constructor, not used as this a Singleton object.
	 */
	protected function __construct() {}

	/**
	 * Prevent cloning of this object.
	 *
	 * @return void
	 */
	private function __clone() {}

	/**
	 * Return the instance of this Singleton object.
	 */
	public static function get_instance(): Blocks_Base {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Register this block.
	 *
	 * @return void
	 */
	public function register(): void {
		// get the block registry.
		$block_registry = WP_Block_Type_Registry::get_instance();

		// bail if block type registry is not available.
		if ( is_null( $block_registry ) ) { // @phpstan-ignore function.impossibleType
			return;
		}

		// bail if block is already registered.
		if ( $block_registry->is_registered( 'provenexpert/' . $this->get_name() ) ) {
			return;
		}

		// register the block.
		register_block_type(
			$this->get_path(),
			array(
				'render_callback' => array( $this, 'render' ),
				'attributes'      => $this->get_attributes(),
			)
		);

		// add settings.
		wp_add_inline_script(
			'provenexpert-' . $this->get_name() . '-editor-script',
			'window.provenexpert_config = ' . wp_json_encode(
				array(
					'enable_fields' => Api::get_instance()->is_prepared() && Api::get_instance()->is_enabled(),
				)
			),
			'before'
		);
	}

	/**
	 * Return the list of attributes for this block.
	 *
	 * @return array<string,mixed>
	 */
	protected function get_attributes(): array {
		$single_attributes = $this->attributes;
		/**
		 * Filter the attributes for a Block.
		 *
		 * @since 1.0.0 Available since 1.0.0
		 *
		 * @param array<string,mixed> $single_attributes The settings as an array.
		 */
		$filter_name = 'provenexpert_block_editor_block_' . $this->get_name() . '_attributes';
		return apply_filters( $filter_name, $single_attributes );
	}

	/**
	 * Return absolute path to JSON of this block.
	 *
	 * @return string
	 */
	protected function get_path(): string {
		$path = Helper::get_plugin_path() . $this->path;

		/**
		 * Filter the path of a Block.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 *
		 * @param string $path The absolute path to the block.json.
		 */
		$filter_name = 'provenexpert_block_editor_block_' . $this->get_name() . '_path';
		return apply_filters( $filter_name, $path );
	}

	/**
	 * Return the internal name of this block.
	 *
	 * @return string
	 */
	public function get_name(): string {
		$name = $this->name;

		$instance = $this;
		/**
		 * Filter the used block name.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param string $name The name.
		 * @param Blocks_Base $instance The block-object.
		 */
		return apply_filters( 'provenexpert_block_editor_block_name', $name, $instance );
	}

	/**
	 * Return the rendered content of this block.
	 *
	 * @param array<string,mixed> $attributes List of attributes for this block.
	 *
	 * @return string
	 */
	public function render( array $attributes ): string {
		return '';
	}
}
