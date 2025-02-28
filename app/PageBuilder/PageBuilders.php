<?php
/**
 * File which handles the page builder support for this plugin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Initialize this plugin.
 */
class PageBuilders {
	/**
	 * Instance of this object.
	 *
	 * @var ?PageBuilders
	 */
	private static ?PageBuilders $instance = null;

	/**
	 * Constructor for this object.
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
	public static function get_instance(): PageBuilders {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Initialize the supported page builders.
	 *
	 * @return void
	 */
	public function init(): void {
		foreach ( $this->get_page_builder() as $page_builder_name ) {
			if ( method_exists( $page_builder_name, 'init' ) ) {
				$obj = call_user_func( $page_builder_name . '::get_instance' );
				if ( $obj instanceof PageBuilder_Base ) {
					$obj->init();
				}
			}
		}
	}

	/**
	 * Return list of supported page builders.
	 *
	 * @return array
	 */
	private function get_page_builder(): array {
		$list = array(
			'\ProvenExpert\PageBuilder\BlockEditor\BlockEditor',
			'\ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets',
			'\ProvenExpert\PageBuilder\Shortcodes\Shortcodes',
		);

		/**
		 * Filter list of supported page builders.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param array $list List of page builders.
		 */
		return apply_filters( 'provenexpert_pagebuilder', $list );
	}
}
