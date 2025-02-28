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
use ProvenExpert\PageBuilder\Shortcodes\Shortcode_Base;
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
	 * @var array
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
	private function __construct() {}

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
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register this block.
	 *
	 * @return void
	 */
	public function register(): void {
		// bail if block is already registered.
		if ( WP_Block_Type_Registry::get_instance()->is_registered( 'provenexpert/' . $this->get_name() ) ) {
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

		// embed translation if available.
		// TODO remove on release.
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'provenexpert-' . $this->get_name() . '-editor-script', 'provenexpert', Helper::get_plugin_path() . 'languages/' );
		}
	}

	/**
	 * Return the list of attributes for this block.
	 *
	 * @return array
	 */
	protected function get_attributes(): array {
		$single_attributes = $this->attributes;
		/**
		 * Filter the attributes for a Block.
		 *
		 * @since 1.0.0 Available since 1.0.0
		 *
		 * @param array $single_attributes The settings as array.
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

		/**
		 * Filter the used block name.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param string $name The name.
		 * @param Shortcode_Base $this The block-object.
		 */
		return apply_filters( 'provenexpert_block_editor_block_name', $name, $this );
	}

	/**
	 * Return the rendered content of this block.
	 *
	 * @param array $attributes List of attributes for this block.
	 *
	 * @return string
	 */
	public function render( array $attributes ): string {
		return '';
	}
}
