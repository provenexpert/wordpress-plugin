<?php
/**
 * File for handling installation of this plugin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Helper-function for plugin-activation and -deactivation.
 */
class Installer {

	/**
	 * Instance of this object.
	 *
	 * @var ?Installer
	 */
	private static ?Installer $instance = null;

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
	public static function get_instance(): Installer {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Activate the plugin.
	 *
	 * Either via activation-hook or via cli-plugin-reset.
	 *
	 * @return void
	 */
	public function activation(): void {
		// set activation runner to enable.
		define( 'PROVENEXPERT_ACTIVATION_RUNNING', 1 );

		if ( is_multisite() ) {
			// loop through the blogs.
			foreach ( Helper::get_blogs() as $blog_id ) {
				// switch to the blog.
				switch_to_blog( $blog_id->blog_id );

				// run tasks for activation in this single blog.
				$this->activation_tasks();
			}

			// switch back to original blog.
			restore_current_blog();
		} else {
			// simply run the tasks on single-site-install.
			$this->activation_tasks();
		}
	}

	/**
	 * Define the tasks to run during activation.
	 *
	 * @return void
	 */
	private function activation_tasks(): void {
		// install tables.
		Init::get_instance()->install_db_tables();

		// initialize the default settings.
		Settings::get_instance()->initialize_options();

		// install schedules.
		Schedules::get_instance()->create_schedules();

		// refresh permalinks.
		update_option( 'provenexpertUpdateSlugs', 1 );

		// show success message on cli.
		Helper::is_cli() ? \WP_CLI::success( 'ProvenExpert activated. Thank you for using our plugin :-)' ) : false;
	}
}
