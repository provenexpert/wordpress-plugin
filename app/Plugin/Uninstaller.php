<?php
/**
 * File for handling uninstallation of this plugin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Api\Api;
use ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets;
use ProvenExpert\ProvenExpertWidgets\Widgets;

/**
 * Helper-function for plugin-activation and -deactivation.
 */
class Uninstaller {
	/**
	 * Instance of this object.
	 *
	 * @var ?Uninstaller
	 */
	private static ?Uninstaller $instance = null;

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
	public static function get_instance(): Uninstaller {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Remove all plugin-data.
	 *
	 * Either via uninstall or via cli.
	 *
	 * @return void
	 */
	public function run(): void {
		// set deactivation runner to enable.
		define( 'PROVENEXPERT_DEACTIVATION_RUNNING', 1 );

		if ( is_multisite() ) {
			// get original blog id.
			$original_blog_id = get_current_blog_id();

			// loop through the blogs.
			foreach ( Helper::get_blogs() as $blog_id ) {
				// switch to the blog.
				switch_to_blog( $blog_id->blog_id );

				// run tasks for deactivation in this single blog.
				$this->deactivation_tasks();
			}

			// switch back to original blog.
			switch_to_blog( $original_blog_id );
		} else {
			// simply run the tasks on single-site-install.
			$this->deactivation_tasks();
		}
	}

	/**
	 * Define the tasks to run during deactivation.
	 *
	 * @return void
	 */
	private function deactivation_tasks(): void {
		// disconnect the plugin from ProvenExpert.
		Api::get_instance()->disconnect();

		// delete the schedules (just to be sure).
		Schedules::get_instance()->delete_all();

		// delete all widget-caches.
		Widgets::get_instance()->delete_cache();

		// delete our custom database-tables.
		Init::get_instance()->delete_db_tables();

		// remove options from settings.
		$settings_obj = Settings::get_instance();
		$settings_obj->set_settings();
		foreach ( $settings_obj->get_settings() as $section_settings ) {
			// bail if no fields are set.
			if ( empty( $section_settings['fields'] ) ) {
				continue;
			}

			// delete each field option.
			foreach ( $section_settings['fields'] as $field_name => $field_settings ) {
				delete_option( $field_name );
			}
		}

		// remove custom options.
		foreach ( $this->get_options() as $option ) {
			delete_option( $option );
		}

		// delete transients.
		foreach ( Transients::get_instance()->get_transients() as $transient ) {
			$transient->delete();
		}

		// unregister classic widgets.
		ClassicWidgets::get_instance()->uninstall();
	}

	/**
	 * Return list of options.
	 *
	 * @return array
	 */
	private function get_options(): array {
		return array(
			'provenExpertVersion',
			'provenexpertUpdateSlugs',
			PROVENEXPERT_TRANSIENTS_LIST,
			PROVENEXPERT_SCHEDULES,
		);
	}
}
