<?php
/**
 * File to handle support for classic widgets.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\ClassicWidgets;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\PageBuilder\PageBuilder_Base;
use WP_Widget;

/**
 * Object to handle the classic widget support.
 */
class ClassicWidgets extends PageBuilder_Base {
	/**
	 * The internal name of this extension.
	 *
	 * @var string
	 */
	protected string $name = 'classic_widgets';

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

		// add our custom classic widgets.
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );

		// call parent init.
		parent::init();
	}

	/**
	 * Return list of available classic widgets.
	 *
	 * @return array
	 */
	public function get_widgets(): array {
		$list = array(
			'ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets\Awards',
			'ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets\Bar',
			'ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets\Circle',
			'ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets\Landing',
			'ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets\ProSeal',
			'ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets\Seal',
		);

		/**
		 * Return list of classic widgets class names.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param array $list List of classic widgets.
		 */
		return apply_filters( 'provenexpert_classic_widgets', $list );
	}

	/**
	 * Add our custom blocks.
	 *
	 * @return void
	 */
	public function register_widgets(): void {
		foreach ( $this->get_widgets() as $widget_class_name ) {
			// bail if name is not a string.
			if ( ! is_string( $widget_class_name ) ) {
				continue;
			}

			// bail if object does not exist.
			if ( ! class_exists( $widget_class_name ) ) {
				continue;
			}

			// get object.
			$obj = new $widget_class_name();

			// bail if object is not from WP_Widget.
			if ( ! $obj instanceof WP_Widget ) {
				continue;
			}

			// register this widget.
			register_widget( $widget_class_name );
		}
	}

	/**
	 * Return whether requirements are met. Block widgets should be disabled.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		return ( function_exists( 'wp_use_widgets_block_editor' ) && ! wp_use_widgets_block_editor() ) || ! function_exists( 'wp_use_widgets_block_editor' );
	}

	/**
	 * Disable our own widgets if Block widgets are enabled OR uninstallation is running.
	 *
	 * @return void
	 */
	public function deactivate(): void {
		// bail if requirements are not met.
		if ( ! $this->is_enabled() ) {
			return;
		}
		$this->uninstall();
	}

	/**
	 * Run this on every uninstallation of this plugin.
	 *
	 * @return void
	 */
	public function uninstall(): void {
		// unregister each classic widget.
		foreach ( $this->get_widgets() as $widget_class_name ) {
			// bail if name is not a string.
			if ( ! is_string( $widget_class_name ) ) {
				continue;
			}

			// bail if object does not exist.
			if ( ! class_exists( $widget_class_name ) ) {
				continue;
			}

			// get object.
			$obj = new $widget_class_name();

			// bail if object is not from WP_Widget.
			if ( ! $obj instanceof WP_Widget ) {
				continue;
			}

			// unregister this widget.
			unregister_widget( $widget_class_name );

			// delete its option.
			delete_option( 'widget_' . strtolower( $obj->id_base ) );
		}
	}
}
