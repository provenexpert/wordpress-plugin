<?php
/**
 * File to handle main functions for single shortcode.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\Shortcodes;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Object to handle main functions for single shortcode.
 */
class Shortcode_Base {
	/**
	 * Internal name of this shortcode.
	 *
	 * @var string
	 */
	protected string $name = '';

	/**
	 * The instance of this object.
	 *
	 * @var Shortcode_Base|null
	 */
	private static ?Shortcode_Base $instance = null;

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
	public static function get_instance(): Shortcode_Base {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Register this shortcode.
	 *
	 * Every shortcode has the following schema: [provenexpert_xy]
	 * Where xy is the internal name of the shortcode.
	 *
	 * Examples:
	 * - [provenexpert_bar]
	 * - [provenexpert_circle]
	 *
	 * Settings can be added via parameter.
	 *
	 * Examples:
	 * - [provenexpert_bar style="aaaa"]
	 *
	 * @return void
	 */
	public function register(): void {
		add_shortcode( 'provenexpert_' . $this->get_name(), array( $this, 'render' ) );
	}

	/**
	 * Return the internal name of this block.
	 *
	 * @return string
	 */
	protected function get_name(): string {
		$name = $this->name;

		/**
		 * Filter the used shortcode name.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param string $name The name.
		 * @param Shortcode_Base $this The shortcode-object.
		 */
		return apply_filters( 'provenexpert_shortcode_name', $name, $this );
	}

	/**
	 * Return the rendered content of this shortcode.
	 *
	 * @param array $attributes List of attributes for this widget.
	 *
	 * @return string
	 */
	public function render( array $attributes ): string {
		return '';
	}
}
