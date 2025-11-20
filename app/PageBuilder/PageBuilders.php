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
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize the supported page builders.
	 *
	 * @return void
	 */
	public function init(): void {
		foreach ( $this->get_page_builder() as $page_builder_name ) {
			// get the object name.
			$obj_name = $page_builder_name . '::get_instance';

			// bail if the object is not callable.
			if ( ! is_callable( $obj_name ) ) {
				continue;
			}

			// get the object.
			$obj = $obj_name();

			// bail if the object could not be loaded.
			if ( ! $obj instanceof PageBuilder_Base ) {
				continue;
			}

			// initialize this object.
			$obj->init();
		}
	}

	/**
	 * Return the list of supported page builders.
	 *
	 * @return array<int,string>
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
		 * @param array<int,string> $list List of page builders.
		 */
		return apply_filters( 'provenexpert_pagebuilder', $list );
	}
}
