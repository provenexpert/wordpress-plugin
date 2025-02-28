<?php
/**
 * File to handle ProvenExpert account data.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Account;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Api\Api;
use ProvenExpert\Api\Request;
use ProvenExpert\Plugin\Helper;
use ProvenExpert\Plugin\Log;
use ProvenExpert\Plugin\Transients;

/**
 * Object to handle ProvenExpert account data.
 */
class Account {
	/**
	 * Instance of this object.
	 *
	 * @var ?Account
	 */
	private static ?Account $instance = null;

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
	public static function get_instance(): Account {
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
		// use admin actions.
		add_action( 'admin_action_provenexpert_check_account_data', array( $this, 'check_via_request' ) );
	}

	/**
	 * Return account info.
	 *
	 * @return array
	 */
	private function get(): array {
		// get account infos.
		$account_info = get_option( 'provenExpertAccount', array() );

		// bail if this is not an array.
		if ( ! is_array( $account_info ) ) {
			return array();
		}

		// return the account info.
		return $account_info;
	}

	/**
	 * Set the account info.
	 *
	 * @param array $account_info The account info from ProvenExpert API.
	 *
	 * @return void
	 */
	public function set( array $account_info ): void {
		update_option( 'provenExpertAccount', $account_info );
	}

	/**
	 * Delete the account information.
	 *
	 * @return void
	 */
	public function delete(): void {
		delete_option( 'provenExpertAccount' );
	}

	/**
	 * Return whether the feature name is available (true) or not (false).
	 *
	 * @param string $feature_name The feature name.
	 *
	 * @return bool
	 */
	public function is_feature_enabled( string $feature_name ): bool {
		// get the account info.
		$account_info = $this->get();

		// return false if no info is set.
		if ( empty( $account_info ) ) {
			return false;
		}

		// bail if requested feature is not available.
		if ( empty( $account_info['profile']['features'][ $feature_name ] ) ) {
			return false;
		}

		// return the info about requested feature as we saved if from ProvenExpert.
		return $account_info['profile']['features'][ $feature_name ];
	}

	/**
	 * Check the account via API.
	 *
	 * @return void
	 */
	public function check(): void {
		// get API object.
		$api_obj = Api::get_instance();

		// bail if no API credentials are set.
		if ( ! $api_obj->is_prepared() ) {
			// log event.
			Log::get_instance()->add_log( __( 'No API credentials set. Therefore cannot retrieve any account information.', 'provenexpert' ), 'info', 'api' );

			// do not process any more tasks here.
			return;
		}

		// collect data for request to ProvenExpert to validate the API credentials.
		$request_obj = new Request();
		$request_obj->set_url( PROVENEXPERT_API_ABOUT );
		$request_obj->set_api_id( $api_obj->get_id() );
		$request_obj->set_api_key( $api_obj->get_key() );
		$request_obj->set_method( 'GET' );
		$request_obj->set_post_data( array() );

		// send the request to the API.
		$request_obj->send();

		// bail if HTTP status is 403 (Access Forbidden) or 404 (Profile not found).
		if ( in_array( $request_obj->get_http_status(), array( 403, 404 ), true ) ) {
			// log event.
			Log::get_instance()->add_log( __( 'ProvenExpert account is blocked or does not exist. Got following value:', 'provenexpert' ) . ' <code>' . $request_obj->get_http_status() . '</code>', 'error', 'api' );

			// remove API ID and key, so user must create new connection until he has access again.
			$api_obj->remove_id();
			$api_obj->remove_key();

			// show info to user in backend.
			$transient_obj = Transients::get_instance()->add();
			$transient_obj->set_name( 'provenexpert_api_account_not_available' );
			$transient_obj->set_type( 'error' );
			/* translators: %1$s will be replaced by an email. */
			$transient_obj->set_message( '<strong>' . __( 'Your ProvenExpert account is not available. This plugin will no longer provide you with ProvenExpert options.', 'provenexpert' ) . '</strong> ' . sprintf( __( 'Please <a href="mailto:%1$s">contact ProvenExpert Support</a> to clarify this.', 'provenexpert' ), Helper::get_support_email() ) );
			$transient_obj->set_dismissible_days( 90 );
			$transient_obj->save();

			// do not process any more tasks here.
			return;
		}

		// bail if HTTP status is not 200 (any not documented error, e.g. not reachable API).
		if ( 200 !== $request_obj->get_http_status() ) {
			// log event.
			Log::get_instance()->add_log( __( 'ProvenExpert API is not available to validate your ProvenExpert account. Got following value:', 'provenexpert' ) . ' <code>' . $request_obj->get_http_status() . '</code>', 'error', 'api' );

			// do not process any more tasks here.
			return;
		}

		// get the response.
		$account_info_json = $request_obj->get_response();

		// bail if response is empty.
		if ( empty( $account_info_json ) ) {
			// log event.
			Log::get_instance()->add_log( __( 'Got faulty response from ProvenExpert API regarding the account infos. Will try again later.', 'provenexpert' ), 'info', 'api' );

			// do not process any more tasks here.
			return;
		}

		// get the account info as array.
		$account_info = json_decode( $account_info_json, ARRAY_A );

		// bail if account info does not contain "status" with value "success".
		if ( ! ( ! empty( $account_info['status'] ) && 'success' === $account_info['status'] ) ) {
			// log event.
			Log::get_instance()->add_log( __( 'Got response from ProvenExpert API that account info was not successfully.', 'provenexpert' ), 'info', 'api' );

			// do not process any more tasks here.
			return;
		}

		// save the response as account info.
		self::get_instance()->set( $account_info );

		// log event.
		Log::get_instance()->add_log( __( 'Got account infos and saved them.', 'provenexpert' ), 'info', 'api' );
	}

	/**
	 * Check the account by request.
	 *
	 * @return void
	 * @noinspection PhpNoReturnAttributeCanBeAddedInspection
	 */
	public function check_via_request(): void {
		// check nonce.
		check_admin_referer( 'provenexpert-check-account-data', 'nonce' );

		// check the account.
		$this->check();

		// show hint if no data are available.
		if ( empty( $this->get() ) ) {
			$transient_obj = Transients::get_instance()->add();
			$transient_obj->set_name( 'provenexpert_account_no_available' );
			$transient_obj->set_type( 'error' );
			/* translators: %1$s will be replaced by a URL, %2$s be an email. */
			$transient_obj->set_message( sprintf( __( 'No data could be retrieved for your ProvenExpert account. Details can be found in the <a href="%1$s">log</a>. If you have any questions, please <a href="mailto:%2$s">contact ProvenExpert support</a>.', 'provenexpert' ), Helper::get_settings_url( 'provenExpert', 'logs' ), Helper::get_support_email() ) );
			$transient_obj->save();
		}

		// forward user to previous page.
		wp_safe_redirect( wp_get_referer() );
		exit;
	}
}
