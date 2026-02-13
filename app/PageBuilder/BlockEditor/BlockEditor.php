<?php
/**
 * File to handle support for pagebuilder Block Editor.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\BlockEditor;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\PageBuilder\PageBuilder_Base;
use ProvenExpert\Plugin\Helper;
use WP_Block_Editor_Context;

/**
 * Object to handle the Block Editor support.
 */
class BlockEditor extends PageBuilder_Base {
	/**
	 * The internal name of this extension.
	 *
	 * @var string
	 */
	protected string $name = 'block_editor';

	/**
	 * Instance of this object.
	 *
	 * @var ?BlockEditor
	 */
	private static ?BlockEditor $instance = null;

	/**
	 * Return the instance of this Singleton object.
	 */
	public static function get_instance(): BlockEditor {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize this PageBuilders support.
	 *
	 * @return void
	 */
	public function init(): void {
		// bail if requirements are not met.
		if ( ! $this->is_enabled() ) {
			return;
		}

		// add our custom blocks.
		add_action( 'init', array( $this, 'register_blocks' ) );

		// add our own category.
		add_filter( 'block_categories_all', array( $this, 'add_block_category' ), 10, 2 );

		// call parent init.
		parent::init();
	}

	/**
	 * Return the list of available blocks.
	 *
	 * @return array<int,string>
	 */
	public function get_blocks(): array {
		$list = array(
			'ProvenExpert\PageBuilder\BlockEditor\Blocks\Awards',
			'ProvenExpert\PageBuilder\BlockEditor\Blocks\Bar',
			'ProvenExpert\PageBuilder\BlockEditor\Blocks\Circle',
			'ProvenExpert\PageBuilder\BlockEditor\Blocks\Landing',
			'ProvenExpert\PageBuilder\BlockEditor\Blocks\ProSeal',
			'ProvenExpert\PageBuilder\BlockEditor\Blocks\Seal',
		);

		/**
		 * Return the list of block class names.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param array $list List of blocks.
		 */
		return apply_filters( 'provenexpert_block_editor_blocks', $list );
	}

	/**
	 * Add our custom blocks.
	 *
	 * @return void
	 */
	public function register_blocks(): void {
		foreach ( $this->get_blocks() as $block_class_name ) {
			// get the object name.
			$obj_name = $block_class_name . '::get_instance';

			// bail if the object is not callable.
			if ( ! is_callable( $obj_name ) ) {
				continue;
			}

			// get the object.
			$obj = $obj_name();

			// bail if the object could not be loaded.
			if ( ! $obj instanceof Blocks_Base ) {
				continue;
			}

			// register this block.
			$obj->register();
		}
	}

	/**
	 * Add our own block category.
	 *
	 * @source https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/#managing-block-categories
	 *
	 * @param array<int,array<string,string|null>> $block_categories The list of categories.
	 * @param WP_Block_Editor_Context              $editor_context The context.
	 *
	 * @return array<int,array<string,string|null>>
	 */
	public function add_block_category( array $block_categories, mixed $editor_context ): array {
		// bail if the given editor context is not "WP_Block_Editor_Context".
		if( ! $editor_context instanceof WP_Block_Editor_Context ) {
			return $block_categories;
		}

		// bail if this is not a post context.
		if ( empty( $editor_context->post ) ) {
			return $block_categories;
		}

		// add the category.
		$block_categories[] = array(
			'slug'  => 'provenexpert',
			'title' => __( 'ProvenExpert', 'provenexpert' ),
			'icon'  => null,
		);

		// return the resulting list of categories.
		return $block_categories;
	}

	/**
	 * Return whether this extension is enabled (true) or not (false).
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		return function_exists( 'register_block_type' ) && ! Helper::is_plugin_active( 'classic-editor/classic-editor.php' );
	}
}
