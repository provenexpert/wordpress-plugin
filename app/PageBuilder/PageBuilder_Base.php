<?php
/**
 * File as base for each pagebuilder support.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Define the base object for page builder support.
 */
class PageBuilder_Base {
	/**
	 * Instance of this object.
	 *
	 * @var ?PageBuilder_Base
	 */
	private static ?PageBuilder_Base $instance = null;

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
	public static function get_instance(): PageBuilder_Base {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Internal name of the page builder.
	 *
	 * @var string
	 */
	protected string $name = '';

	/**
	 * This extension can be enabled by user.
	 *
	 * @var bool
	 */
	protected bool $can_be_enabled_by_user = false;

	/**
	 * Initialize the Page Builder support.
	 *
	 * @return void
	 */
	public function init(): void {}

	/**
	 * Return widgets this page builder supports.
	 *
	 * This means any widgets, block, component ... name it.
	 *
	 * @return array
	 */
	public function get_widgets(): array {
		return array();
	}

	/**
	 * Return the internal name of the page builder.
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Return whether this extension is enabled (true) or not (false).
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		return true;
	}

	/**
	 * Tasks to run during uninstallation of this plugin for this page builder support.
	 *
	 * @return void
	 */
	public function uninstall(): void {}
}
