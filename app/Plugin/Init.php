<?php
/**
 * File with main initializer for this plugin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Account\Account;
use ProvenExpert\Api\Api;
use ProvenExpert\PageBuilder\PageBuilders;
use ProvenExpert\Plugin\Admin\Admin;

/**
 * Initialize this plugin.
 */
class Init {
	/**
	 * Instance of this object.
	 *
	 * @var ?Init
	 */
	private static ?Init $instance = null;

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
	public static function get_instance(): Init {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Initialize this plugin.
	 *
	 * @return void
	 */
	public function init(): void {
		// initialize the transients.
		Transients::get_instance()->init();

		// initialize settings.
		Settings::get_instance()->init();

		// initialize the scheduler.
		Schedules::get_instance()->init();

		if ( is_admin() ) {
			// init wp-admin-support.
			Admin::get_instance()->init();
		}

		// initialize page builder.
		PageBuilders::get_instance()->init();

		// initialize API hooks.
		API::get_instance()->init();

		// initialize Account hooks.
		Account::get_instance()->init();

		// initialize the setup.
		Setup::get_instance()->init();

		// register cli.
		add_action( 'cli_init', array( $this, 'cli' ) );

		// misc.
		add_filter( 'plugin_action_links_' . plugin_basename( PROVENEXPERT_PLUGIN ), array( Setup::get_instance(), 'add_setting_link' ) );
		global $wp_version;
		if ( version_compare( $wp_version, '5.1.0', '>' ) ) {
			add_filter( 'http_request_reject_unsafe_urls', array( $this, 'allow_own_safe_domain' ), 10, 2 );
		} else {
			add_filter( 'http_request_reject_unsafe_urls', '__return_false' );
		}
	}

	/**
	 * Run on activation of this plugin.
	 *
	 * @return void
	 */
	public function activation(): void {
		Installer::get_instance()->activation();
	}

	/**
	 * Run on deactivation of this plugin.
	 *
	 * @return void
	 */
	public function deactivation(): void {
		// remove our own schedules.
		add_filter( 'provenexpert_disable_cron_check', '__return_true' );
		Schedules::get_instance()->delete_all();
	}

	/**
	 * Install db-table of registered objects.
	 *
	 * @return void
	 */
	public function install_db_tables(): void {
		$objects = array(
			'ProvenExpert\Plugin\Log',
		);
		/**
		 * Add additional objects for this plugin which use custom tables.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param array $objects List of objects.
		 */
		foreach ( apply_filters( 'provenexpert_objects_with_db_tables', $objects ) as $obj_name ) {
			if ( method_exists( $obj_name, 'create_table' ) ) {
				$obj = call_user_func( $obj_name . '::get_instance' );
				$obj->create_table();
			}
		}
	}

	/**
	 * Delete the tables.
	 *
	 * @return void
	 */
	public function delete_db_tables(): void {
		$objects = array(
			'ProvenExpert\Plugin\Log',
		);
		/**
		 * Add additional objects for this plugin which use custom tables.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param array $objects List of objects.
		 */
		foreach ( apply_filters( 'provenexpert_objects_with_db_tables', $objects ) as $obj_name ) {
			if ( method_exists( $obj_name, 'delete_table' ) ) {
				$obj = call_user_func( $obj_name . '::get_instance' );
				$obj->delete_table();
			}
		}
	}

	/**
	 * Register WP-CLI.
	 *
	 * @return void
	 */
	public function cli(): void {
		\WP_CLI::add_command( 'provenexpert', 'ProvenExpert\Plugin\Cli' );
	}

	/**
	 * Prepare kses-filter for any form output.
	 *
	 * @return void
	 */
	public function prepare_kses(): void {
		add_filter( 'wp_kses_allowed_html', array( $this, 'allow_fields_in_kses' ), 10, 2 );
	}

	/**
	 * Get allowed fields for kses.
	 *
	 * @param array  $html The allowed HTML-entities with its attributes.
	 * @param string $context The context.
	 *
	 * @return array
	 */
	public function allow_fields_in_kses( array $html, string $context ): array {
		// bail if it is not a context compatible with our cpt.
		if ( 'post' !== $context ) {
			return $html;
		}

		// allow scripts.
		if ( empty( $html['script'] ) ) {
			$html['script'] = array(
				'src'    => true,
				'onload' => true,
			);
		}

		if ( empty( $html['noscript'] ) ) {
			$html['noscript'] = array();
		}

		// return resulting list of allowed HTML-entities.
		return $html;
	}

	/**
	 * Allow ProvenExpert-URLs for requests.
	 *
	 * @param bool   $return_value True if the domain in the URL is safe.
	 * @param string $url The requested URL.
	 *
	 * @return bool
	 */
	public function allow_own_safe_domain( bool $return_value, string $url ): bool {
		if ( strpos( $url, wp_parse_url( PROVENEXPERT_API_PLUGINS, PHP_URL_HOST ) ) ) {
			return true;
		}
		return $return_value;
	}
}
