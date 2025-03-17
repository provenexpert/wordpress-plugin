<?php
/**
 * File for handling tasks in wp-admin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Admin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Plugin\Helper;
use ProvenExpert\ProvenExpertSeals\Seals;
use ProvenExpert\ProvenExpertWidgets\Widgets;

/**
 * Helper-function for tasks in wp-admin.
 */
class Admin {
	/**
	 * Instance of this object.
	 *
	 * @var ?Admin
	 */
	private static ?Admin $instance = null;

	/**
	 * Constructor for this object.
	 */
	private function __construct() {
	}

	/**
	 * Prevent cloning of this object.
	 *
	 * @return void
	 */
	private function __clone() {
	}

	/**
	 * Return the instance of this Singleton object.
	 */
	public static function get_instance(): Admin {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Initialize the wp-admin support.
	 *
	 * @return void
	 */
	public function init(): void {
		// enqueue scripts and styles.
		add_action( 'admin_enqueue_scripts', array( $this, 'add_styles_and_js' ), PHP_INT_MAX );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_dialog' ), PHP_INT_MAX );

		// add action hooks.
		add_action( 'admin_action_provenexpert_clear_cache', array( $this, 'clear_cache_by_request' ) );
		add_action( 'admin_action_provenexpert_delete_all_logs', array( $this, 'delete_all_logs_by_request' ) );
	}

	/**
	 * Add own CSS and JS for backend.
	 *
	 * @return void
	 */
	public function add_styles_and_js(): void {
		// admin-specific styles.
		wp_enqueue_style(
			'provenexpert-admin',
			Helper::get_plugin_url() . 'admin/styles.css',
			array(),
			Helper::get_file_version( Helper::get_plugin_path() . 'admin/styles.css' ),
		);

		// backend-JS.
		wp_enqueue_script(
			'provenexpert-admin',
			Helper::get_plugin_url() . 'admin/js.js',
			array( 'jquery', 'easy-dialog-for-wordpress' ),
			Helper::get_file_version( Helper::get_plugin_path() . 'admin/js.js' ),
			true
		);

		// add php-vars to our js-script.
		wp_localize_script(
			'provenexpert-admin',
			'provenExpertJsVars',
			array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'review_url'    => Helper::get_review_url(),
				'title_rate_us' => __( 'Rate this plugin', 'provenexpert' ),
				'dismiss_nonce' => wp_create_nonce( 'provenexpert-dismiss-nonce' ),
			)
		);

		// add extended color-picker.
		wp_enqueue_style( 'wp-color-picker' );
		wp_register_script(
			'wp-color-picker-alpha',
			Helper::get_plugin_url() . 'lib/wp-color-picker-alpha.min.js',
			array( 'wp-color-picker' ),
			Helper::get_file_version( Helper::get_plugin_path() . 'lib/wp-color-picker-alpha.min.js' ),
			true
		);
		wp_enqueue_script( 'wp-color-picker-alpha' );
	}

	/**
	 * Add the dialog-scripts and -styles.
	 *
	 * @return void
	 */
	public function add_dialog(): void {
		// embed necessary scripts for dialog.
		$path = Helper::get_plugin_path() . 'vendor/threadi/easy-dialog-for-wordpress/';
		$url  = Helper::get_plugin_url() . 'vendor/threadi/easy-dialog-for-wordpress/';

		// bail if path does not exist.
		if ( ! file_exists( $path ) ) {
			return;
		}

		// embed the dialog-components JS-script.
		$script_asset_path = $path . 'build/index.asset.php';

		// bail if script does not exist.
		if ( ! file_exists( $script_asset_path ) ) {
			return;
		}

		// embed script.
		$script_asset = require $script_asset_path;
		wp_enqueue_script(
			'easy-dialog-for-wordpress',
			$url . 'build/index.js',
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		// embed the dialog-components CSS-file.
		$admin_css      = $url . 'build/style-index.css';
		$admin_css_path = $path . 'build/style-index.css';
		wp_enqueue_style(
			'easy-dialog-for-wordpress',
			$admin_css,
			array( 'wp-components' ),
			Helper::get_file_version( $admin_css_path )
		);
	}

	/**
	 * Clear cache by request.
	 *
	 * @return void
	 * @noinspection PhpNoReturnAttributeCanBeAddedInspection
	 */
	public function clear_cache_by_request(): void {
		// check nonce.
		check_admin_referer( 'provenexpert-clear-cache', 'nonce' );

		// clean the cache.
		Widgets::get_instance()->delete_cache();
		Seals::get_instance()->delete_cache();

		// forward user to previous page.
		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	/**
	 * Delete all logs.
	 *
	 * @return void
	 * @noinspection PhpNoReturnAttributeCanBeAddedInspection
	 */
	public function delete_all_logs_by_request(): void {
		// check nonce.
		check_admin_referer( 'provenexpert-delete-all-logs', 'nonce' );

		// delete all entries of logs.
		\ProvenExpert\Plugin\Log::get_instance()->truncate_table();

		// show success-message.
		$transients_obj = \ProvenExpert\Plugin\Transients::get_instance();
		$transient_obj  = $transients_obj->add();
		$transient_obj->set_name( 'provenexpert_logs_deleted' );
		$transient_obj->set_message( '<strong>' . __( 'All logs has been deleted.', 'provenexpert' ) . '</strong>' );
		$transient_obj->set_type( 'success' );
		$transient_obj->save();

		// Log event.
		\ProvenExpert\Plugin\Log::get_instance()->add_log( __( 'All previous logs has been deleted', 'provenexpert' ), 'success', 'system' );

		// redirect user back to list.
		wp_safe_redirect( wp_get_referer() );
		exit;
	}
}
