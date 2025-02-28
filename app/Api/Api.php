<?php
/**
 * File to handle general API-tasks.
 *
 * @docs https://www.provenexpert.com/restapi/v1/docs/
 *
 * @package provenexpert
 */

namespace ProvenExpert\Api;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Account\Account;
use ProvenExpert\Plugin\Crypt;
use ProvenExpert\Plugin\Helper;
use ProvenExpert\Plugin\Log;
use ProvenExpert\Plugin\Transients;

/**
 * Object to handle API tasks.
 */
class Api {
	/**
	 * Instance of this object.
	 *
	 * @var ?Api
	 */
	private static ?Api $instance = null;

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
	public static function get_instance(): Api {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Initialize API hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		// use hooks.
		add_action( 'wp', array( $this, 'update_slugs' ) );

		// use our own hooks.
		add_filter( 'provenexpert_log_categories', array( $this, 'add_log_category' ) );

		// use actions.
		add_action( 'admin_action_provenexpert_disconnect', array( $this, 'disconnect_by_request' ) );
	}

	/**
	 * Update slugs on request.
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function update_slugs(): void {
		if ( 1 !== absint( get_option( 'provenexpertUpdateSlugs' ) ) ) {
			return;
		}

		flush_rewrite_rules();
		update_option( 'provenexpertUpdateSlugs', 0 );
	}

	/**
	 * Return the API id.
	 *
	 * @return string
	 */
	public function get_id(): string {
		return Crypt::get_instance()->decrypt( get_option( 'provenExpertApiId' ) );
	}

	/**
	 * Set the API ID.
	 *
	 * @param string $api_id The API ID to save.
	 *
	 * @return void
	 */
	private function set_id( string $api_id ): void {
		update_option( 'provenExpertApiId', Crypt::get_instance()->encrypt( $api_id ) );
	}

	/**
	 * Remove the API ID.
	 *
	 * @return void
	 */
	public function remove_id(): void {
		update_option( 'provenExpertApiId', '' );
	}

	/**
	 * Return the API key.
	 *
	 * @return string
	 */
	public function get_key(): string {
		return Crypt::get_instance()->decrypt( get_option( 'provenExpertApiKey' ) );
	}

	/**
	 * Set the API key.
	 *
	 * @param string $api_key The API key to save.
	 *
	 * @return void
	 */
	private function set_key( string $api_key ): void {
		update_option( 'provenExpertApiKey', Crypt::get_instance()->encrypt( $api_key ) );
	}

	/**
	 * Remove the API key.
	 *
	 * @return void
	 */
	public function remove_key(): void {
		update_option( 'provenExpertApiKey', '' );
	}

	/**
	 * Return whether the API is prepared.
	 *
	 * @return bool
	 */
	public function is_prepared(): bool {
		return ! empty( $this->get_id() ) && ! empty( $this->get_key() );
	}

	/**
	 * Show error if API is not prepared.
	 *
	 * If user is logged in show error.
	 * If he is not show nothing.
	 *
	 * @return string
	 */
	public function show_api_not_prepared(): string {
		// bail if user is not logged in.
		if ( ! is_user_logged_in() ) {
			return '';
		}

		// return the error message with option to go to settings.
		return '<div class="provenexpert-error"><p>' . __( 'API is not configured.', 'provenexpert' ) . '</p><a href="' . esc_url( Helper::get_settings_url() ) . '" target="_blank"><span class="dashicons dashicons-admin-settings"></span></a></div>';
	}

	/**
	 * Show error if API is disabled.
	 *
	 * If user is logged in show error.
	 * If he is not show nothing.
	 *
	 * @return string
	 */
	public function show_api_disabled(): string {
		// bail if user is not logged in.
		if ( ! is_user_logged_in() ) {
			return '';
		}

		// return the error message with option to go to settings.
		/* translators: %1$s will be replaced by the log URL. */
		return '<div class="provenexpert-hint"><p>' . sprintf( __( 'The ProvenExpert API is disabled. Check <a href="%1$s" target="_blank">your logs (opens new window)</a>', 'provenexpert' ), esc_url( Helper::get_settings_url( 'provenExpert', 'logs' ) ) ) . '</p></div>';
	}

	/**
	 * Add our own category for logging.
	 *
	 * @param array $category_list List of log categories.
	 *
	 * @return array
	 */
	public function add_log_category( array $category_list ): array {
		$category_list['api'] = __( 'API', 'provenexpert' );
		return $category_list;
	}

	/**
	 * Return whether the API is enabled.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		return 1 !== absint( get_option( 'provenExpertApiDisabled' ) );
	}

	/**
	 * Set the API to disable (without changing credentials).
	 *
	 * @return void
	 */
	public function set_enabled(): void {
		update_option( 'provenExpertApiDisabled', 0 );
	}

	/**
	 * Set the API to disable (without changing credentials).
	 *
	 * @return void
	 */
	public function set_disabled(): void {
		update_option( 'provenExpertApiDisabled', 1 );
	}

	/**
	 * Connect this plugin to the API of ProvenExpert by request.
	 *
	 * @return bool
	 */
	public function connect_by_request(): bool {
		// get the pluginId.
		$plugin_id = filter_input( INPUT_GET, 'pluginId', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// get the setup token.
		$setup_token = filter_input( INPUT_GET, 'setupToken', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// get the clientId.
		$client_id = filter_input( INPUT_GET, 'clientId', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// bail if one of the values is not set.
		if ( empty( $plugin_id ) || empty( $setup_token ) || empty( $client_id ) ) {
			return false;
		}

		// call the connect function to get the result.
		return $this->connect( $plugin_id, $setup_token );
	}

	/**
	 * Connect this plugin to the API of ProvenExpert.
	 *
	 * @param string $plugin_id   The plugin ID.
	 * @param string $setup_token The setup token.
	 *
	 * @return bool
	 */
	public function connect( string $plugin_id, string $setup_token ): bool {
		// get the log object.
		$log = Log::get_instance();

		// get the transient object.
		$transients_obj = Transients::get_instance();

		// save the plugin id.
		$this->set_plugin_id( $plugin_id );

		$request_obj = new Request();
		$request_obj->set_url( PROVENEXPERT_API_PLUGINS . $plugin_id . '/connect' );
		$request_obj->set_method( 'POST' );
		$request_obj->set_post_data( array( 'setupToken' => $setup_token ) );
		$request_obj->set_post_data_json_encode( true );

		// send request to API.
		$request_obj->send();

		// get http status.
		$http_status = $request_obj->get_http_status();

		// bail if http status is not 200.
		if ( 200 !== $http_status ) {
			// log event.
			$log->add_log( __( 'Unexpected HTTP-Status from ProvenExpert:', 'provenexpert' ) . ' <code>' . esc_html( $http_status ) . '</code>', 'error', 'api' );

			// return the default template.
			return false;
		}

		// get content from response.
		$content = $request_obj->get_response();

		// bail if content is empty.
		if ( empty( $content ) ) {
			// log event.
			$log->add_log( __( 'Got empty response from ProvenExpert!', 'provenexpert' ), 'error', 'api' );

			// return the default template.
			return false;
		}

		// get content as array.
		$content_array = json_decode( $content, ARRAY_A );

		// bail if status is not given.
		if ( empty( $content_array['status'] ) ) {
			// log event.
			$log->add_log( __( 'Got empty response from ProvenExpert!', 'provenexpert' ), 'error', 'api' );

			// return the default template.
			return false;
		}

		// bail if status is not success.
		if ( 'success' !== $content_array['status'] ) {
			// log event.
			$log->add_log( __( 'Plugin could not be connected to ProvenExpert!', 'provenexpert' ), 'error', 'api' );

			// return the default template.
			return false;
		}

		// bail if apiUser or apiKey is not given.
		if ( empty( $content_array['connect']['apiUser'] ) || empty( $content_array['connect']['apiKey'] ) ) {
			// log event.
			$log->add_log( __( 'ProvenExpert does not send API credentials!', 'provenexpert' ), 'error', 'api' );

			// return the default template.
			return false;
		}

		// save given API credentials as encrypted values.
		$this->set_id( $content_array['connect']['apiUser'] );
		$this->set_key( $content_array['connect']['apiKey'] );

		// request the account info.
		Account::get_instance()->check();

		// collect the message.
		$message = '<strong>' . __( 'Your ProvenExpert account is now connected to your WordPress website.', 'provenexpert' ) . '</strong>';
		/* translators: %1$s will be replaced with a URL. */
		$message .= ' ' . sprintf( __( 'You are now able to use the ProvenExpert widgets in your website. You can see which widgets you can use <a href="%1$s">here</a>.', 'provenexpert' ), esc_url( Helper::get_settings_url( 'provenExpert', 'widgets' ) ) );

		// show info to user.
		$transient_obj = $transients_obj->add();
		$transient_obj->set_name( 'provenexpert_api_connected' );
		$transient_obj->set_type( 'success' );
		$transient_obj->set_message( $message );
		$transient_obj->save();

		// log event.
		$log->add_log( __( 'Got API credentials from ProvenExpert and saved them.', 'provenexpert' ), 'success', 'api' );

		// return true as all is ok.
		return true;
	}

	/**
	 * Disconnect this plugin from ProvenExpert by request.
	 *
	 * @return void
	 * @noinspection PhpNoReturnAttributeCanBeAddedInspection
	 */
	public function disconnect_by_request(): void {
		// check nonce.
		check_admin_referer( 'provenexpert-disconnect', 'nonce' );

		// run the disconnect.
		$this->disconnect();

		// forward user to previous page.
		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	/**
	 * Disconnect this plugin from ProvenExpert.
	 *
	 * @return void
	 */
	public function disconnect(): void {
		// get the plugin id.
		$plugin_id = $this->get_plugin_id();

		// get client ID if plugin ID is not set.
		if ( empty( $plugin_id ) ) {
			// get the client ID.
			$plugin_id = Crypt::get_instance()->get_method()->get_hash();

			// bail if also client ID is not set.
			if ( empty( $plugin_id ) ) {
				// log event.
				Log::get_instance()->add_log( __( 'No plugin ID available. Disconnect impossible.', 'provenexpert' ), 'error', 'api' );

				// do not process any more tasks here.
				return;
			}
		}

		// bail if no API ID and key are set.
		if ( empty( $this->get_id() ) || empty( $this->get_key() ) ) {
			// log event.
			Log::get_instance()->add_log( __( 'No API ID and key available. Disconnect impossible.', 'provenexpert' ), 'error', 'api' );

			// do not process any more tasks here.
			return;
		}

		// send request to ProvenExpert to disconnect this plugin.
		$request_obj = new Request();
		$request_obj->set_url( PROVENEXPERT_API_PLUGINS . $plugin_id );
		$request_obj->set_api_id( $this->get_id() );
		$request_obj->set_api_key( $this->get_key() );
		$request_obj->set_method( 'DELETE' );
		$request_obj->set_post_data( array() );

		// send request to API.
		$request_obj->send();

		// get the response.
		$http_status = $request_obj->get_http_status();

		// bail if status is 404.
		if ( 404 === $http_status ) {
			// log event.
			Log::get_instance()->add_log( __( 'Plugin is already disconnected from ProvenExpert.', 'provenexpert' ), 'error', 'api' );

			// do not process any more tasks here.
			return;
		}

		// delete API ID and Key.
		$this->remove_id();
		$this->remove_key();

		// delete the account infos.
		Account::get_instance()->delete();

		// log event.
		Log::get_instance()->add_log( __( 'Plugin has been disconnected from ProvenExpert.', 'provenexpert' ), 'success', 'api' );
	}

	/**
	 * Return the plugin ID.
	 *
	 * @return string
	 */
	private function get_plugin_id(): string {
		return get_option( 'provenExpertPluginId', '' );
	}

	/**
	 * Set the plugin ID.
	 *
	 * @param string $plugin_id The plugin ID.
	 *
	 * @return void
	 */
	private function set_plugin_id( string $plugin_id ): void {
		// set plugin ID.
		update_option( 'provenExpertPluginId', $plugin_id );

		// log event.
		Log::get_instance()->add_log( __( 'Saved pluginId from ProvenExpert:', 'provenexpert' ) . ' <code>' . $plugin_id . '</code>', 'info', 'api' );
	}
}
