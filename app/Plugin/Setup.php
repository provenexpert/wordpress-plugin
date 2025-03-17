<?php
/**
 * File with main initializer for this plugin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Api\Api;

/**
 * Initialize the setup for this plugin.
 */
class Setup {
	/**
	 * Instance of this object.
	 *
	 * @var ?Setup
	 */
	private static ?Setup $instance = null;

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
	public static function get_instance(): Setup {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Initialize this object.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'admin_init', array( $this, 'show_setup_hint' ) );
		add_action( 'init', array( $this, 'add_setup_rewrite_rule' ), 10, 0 );
		add_filter( 'query_vars', array( $this, 'add_setup_vars' ) );
		add_filter( 'template_include', array( $this, 'check_for_setup_return' ), 20 );
	}

	/**
	 * Check if setup should be run and show hint for it.
	 *
	 * @return void
	 */
	public function show_setup_hint(): void {
		// get transients object.
		$transients_obj = Transients::get_instance();

		// bail if API is configured.
		if ( Api::get_instance()->is_prepared() ) {
			$transients_obj->get_transient_by_name( 'provenexpert_start_setup_hint' )->delete();
			return;
		}

		// bail if hint is already set.
		if ( $transients_obj->get_transient_by_name( 'provenexpert_start_setup_hint' )->is_set() ) {
			return;
		}

		// delete all other transients from our plugin.
		foreach ( $transients_obj->get_transients() as $transient_obj ) {
			$transient_obj->delete();
		}

		// add hint to run setup.
		$transient_obj = $transients_obj->add();
		$transient_obj->set_name( 'provenexpert_start_setup_hint' );
		$transient_obj->set_message( __( '<strong>You have installed ProvenExpert - nice and thank you!</strong> To be able to use the plugins features, please connect it to your ProvenExpert account. Click on the following button. Confirm the connection to your WordPress on the following page.', 'provenexpert' ) . '<br><br>' . sprintf( '<a href="%1$s" class="button button-primary">' . __( 'Connect with ProvenExpert', 'provenexpert' ) . '</a>', esc_url( $this->get_setup_link() ) ) );
		$transient_obj->set_type( 'error' );
		$transient_obj->set_dismissible_days( 2 );
		$transient_obj->save();
	}

	/**
	 * Return the setup magic link.
	 *
	 * Hint: does not use endpoint /plugins/connect-from-plugins as this function is run on multiple positions
	 * in WordPress backend. Using /plugins/connect-init-session reduces the API requests for this task.
	 *
	 * @return string
	 */
	public function get_setup_link(): string {
		// get the installation hash.
		$hash = Crypt::get_instance()->get_method()->get_hash();

		// create return URL depending on permalink settings.
		$return_url = add_query_arg(
			array(
				'provenexpertplugin' => 1,
				'hash'               => $hash,
			),
			trailingslashit( get_option( 'home' ) )
		);
		if ( ! empty( get_option( 'permalink_structure' ) ) ) {
			$return_url = get_option( 'home' ) . '/provenexpertplugin/' . $hash . '/';
		}

		// collect the URL.
		$url = add_query_arg(
			array(
				'integration' => 'wordpress',
				'returnUrl'   => $return_url,
				'clientId'    => $hash,
			),
			'https://www.provenexpert.com/restapi/v1/plugins/connect-init-session'
		);

		// add version if developer mode is not enabled.
		if ( function_exists( 'wp_is_development_mode' ) && false === wp_is_development_mode( 'plugin' ) ) {
			$url = add_query_arg( array( 'pluginVersion' => PROVENEXPERT_VERSION ), $url );
		}

		// return the URL.
		return $url;
	}

	/**
	 * Add rewrite rule for return URL during setup via magic link.
	 *
	 * @return void
	 */
	public function add_setup_rewrite_rule(): void {
		add_rewrite_rule( 'provenexpertplugin/([a-z0-9-]+)/?$', 'index.php?provenexpert=$matches[1]', 'top' );
	}

	/**
	 * Add our custom query var during setup.
	 *
	 * @param array $query_vars List of query vars.
	 *
	 * @return array
	 */
	public function add_setup_vars( array $query_vars ): array {
		$query_vars[] = 'provenexpert';
		return $query_vars;
	}

	/**
	 * Check for magic link return URL request.
	 *
	 * @param string $template The called template.
	 *
	 * @return string
	 */
	public function check_for_setup_return( string $template ): string {
		// bail if user is not logged in.
		if ( ! is_user_logged_in() ) {
			return $template;
		}

		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return $template;
		}

		// get our query var.
		$provenexpert = get_query_var( 'provenexpert' );

		// if no provenexpert is set, check the URL-param.
		if ( empty( $provenexpert ) ) {
			$provenexpert = filter_input( INPUT_GET, 'provenexpertplugin', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		}

		// bail if our query var is not set.
		if ( empty( $provenexpert ) ) {
			return $template;
		}

		// connect to ProvenExpert.
		if ( ! Api::get_instance()->connect_by_request() ) {
			return $template;
		}

		// forward to our welcome page.
		wp_safe_redirect( Helper::get_url_of_post_type_with_post_posts() );
		exit;
	}

	/**
	 * Add link to plugin-settings in plugin-list.
	 *
	 * @param array $links List of links.
	 * @return array
	 */
	public function add_setting_link( array $links ): array {
		if ( Api::get_instance()->is_prepared() ) {
			// adds the link to for settings.
			$links[] = "<a href='" . esc_url( Helper::get_settings_url() ) . "'>" . __( 'Settings', 'provenexpert' ) . '</a>';
		} else {
			// adds the link to for setup.
			$links[] = "<a href='" . esc_url( $this->get_setup_link() ) . "' style='font-weight: bold'>" . __( 'Connect with ProvenExpert', 'provenexpert' ) . '</a>';
		}

		// return resulting list of links.
		return $links;
	}
}
