<?php
/**
 * File to handle the basic settings and functions for each Proven Expert seal.
 *
 * @source https://developer.provenexpert.com
 *
 * @package provenexpert
 */

namespace ProvenExpert\ProvenExpertSeals;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Api\Api;
use ProvenExpert\Api\Request;
use ProvenExpert\Plugin\Helper;
use ProvenExpert\Plugin\Languages;
use ProvenExpert\Plugin\Log;
use ProvenExpert\Plugin\Object_Base;

/**
 * Object to handle the basic settings and functions for each Proven Expert seal.
 */
class Seal_Base extends Object_Base {

	/**
	 * The seals type.
	 *
	 * @var string
	 */
	protected string $type = '';

	/**
	 * The HTML-code of this seal.
	 *
	 * @var string
	 */
	protected string $html = '';

	/**
	 * Instance of this object.
	 *
	 * @var ?Seal_Base
	 */
	private static ?Seal_Base $instance = null;

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
	public static function get_instance(): Seal_Base {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Return the type of this seal.
	 *
	 * @return string
	 */
	public function get_type(): string {
		return $this->type;
	}

	/**
	 * Return the HTML of this seal-object.
	 *
	 * @return string
	 */
	public function get_html(): string {
		// get API object.
		$api_obj = Api::get_instance();

		// bail if API is not prepared.
		if ( ! $api_obj->is_prepared() ) {
			return $api_obj->show_api_not_prepared();
		}

		// bail if API is disabled.
		if ( ! $api_obj->is_enabled() ) {
			return $api_obj->show_api_disabled();
		}

		// bail if widget is not usable.
		if ( ! $this->is_usable() ) {
			return $this->show_not_usable();
		}

		// if html is empty, get if from DB-cache depending on settings of this object.
		if ( empty( $this->html ) ) {
			$this->html = get_option( 'provenExpertSeal' . $this->get_md5(), '' );
		}

		// if html is still empty, get it from API.
		if ( empty( $this->html ) ) {
			$this->update();
			$this->html = get_option( 'provenExpertSeal' . $this->get_md5(), '' );
		}

		// return the resulting html code.
		return $this->html;
	}

	/**
	 * Update the cached HTML-code of this seal via request to the ProvenExpert API.
	 *
	 * Steps:
	 * 1. Send request with configuration.
	 * 2. Get the seal id from response.
	 * 3. Send second request with HTML-code.
	 *
	 * @return void
	 */
	public function update(): void {
		// get the API object.
		$api_obj = Api::get_instance();

		// bail if credentials not set.
		if ( ! $api_obj->is_prepared() ) {
			return;
		}

		// bail if API is disabled.
		if ( ! Api::get_instance()->is_enabled() ) {
			return;
		}

		// define the first request with the configuration.
		$request_obj = new Request();
		$request_obj->set_url( PROVENEXPERT_API_SEAL_URL . $this->get_type() );
		$request_obj->set_api_id( $api_obj->get_id() );
		$request_obj->set_api_key( $api_obj->get_key() );
		$request_obj->set_post_data( $this->get_config() );
		$request_obj->set_post_data_json_encode( true );

		// send request to API.
		$request_obj->send();

		// if result is 200 or 201, response is OK.
		if ( in_array( $request_obj->get_http_status(), array( 200, 201 ), true ) ) {
			// get the response.
			$response = $request_obj->get_response();

			// decode the response.
			$response_array = json_decode( $response, ARRAY_A );

			// bail if status is "error".
			if ( ! empty( $response_array['status'] ) && 'error' === $response_array['status'] && ! empty( $response_array['errors'] ) ) {
				// log event.
				Log::get_instance()->add_log( __( 'API-request resulted in error:', 'provenexpert' ) . ' <code>' . wp_json_encode( $response_array['errors'] ) . '</code>', 'error', 'api' );

				// if response contains "wrong credentials" clear the seal cache.
				if ( in_array( 'wrong credentials', $response_array['errors'], true ) ) {
					// log event.
					/* translators: %1$s will be replaced by the settings URL. */
					Log::get_instance()->add_log( sprintf( __( 'Your API credentials are wrong. You will not be able to use any widgets or seals. Check <a href="%1$s">your settings</a>.', 'provenexpert' ), esc_url( Helper::get_settings_url() ) ), 'error', 'api' );

					// disable the API.
					Api::get_instance()->set_disabled();

					// delete cache.
					Seals::get_instance()->delete_cache();
				}

				// do nothing more.
				return;
			}

			// get the seal ID from response.
			if ( ! empty( $response_array['seal']['id'] ) ) {
				// send second request to get the HTML-code.
				$request_obj = new Request();
				$request_obj->set_url( PROVENEXPERT_API_SEAL_URL . $this->get_type() . '/' . $response_array['seal']['id'] . '/snippet' );
				$request_obj->set_api_id( $api_obj->get_id() );
				$request_obj->set_api_key( $api_obj->get_key() );
				$request_obj->set_post_data( array() );
				$request_obj->set_method( 'GET' );

				// send request to API.
				$request_obj->send();

				// if result is 200 or 201, response is OK.
				if ( in_array( $request_obj->get_http_status(), array( 200, 201 ), true ) ) {
					// get the response.
					$response = $request_obj->get_response();

					// decode the response.
					$response_array = json_decode( $response, ARRAY_A );

					// bail if status is "error".
					if ( ! empty( $response_array['status'] ) && 'error' === $response_array['status'] && ! empty( $response_array['errors'] ) ) {
						// log event.
						Log::get_instance()->add_log( __( 'API-request resulted in error:', 'provenexpert' ) . ' <code>' . wp_json_encode( $response_array['errors'] ) . '</code>', 'error', 'api' );

						// if response contains "wrong credentials" clear the seal cache.
						if ( in_array( 'wrong credentials', $response_array['errors'], true ) ) {
							// log event.
							/* translators: %1$s will be replaced by the settings URL. */
							Log::get_instance()->add_log( sprintf( __( 'Your API credentials are wrong. You will not be able to use any widgets or seals. Check <a href="%1$s">your settings</a>.', 'provenexpert' ), esc_url( Helper::get_settings_url() ) ), 'error', 'api' );

							// disable the API.
							Api::get_instance()->set_disabled();

							// delete cache.
							Seals::get_instance()->delete_cache();
						}

						// do nothing more.
						return;
					}

					// bail if html code is empty.
					if ( empty( $response_array['snippet']['htmlCode'] ) ) {
						Log::get_instance()->add_log( __( 'No HTML-code returned for requested seal.', 'provenexpert' ), 'error', 'api' );
						return;
					}

					// save the response so seal can use it.
					update_option( 'provenExpertSeal' . $this->get_md5(), $response_array['snippet']['htmlCode'] );

					// add this seal to the list of provenexpert_seals.
					Seals::get_instance()->add_seal_with_code( $this->get_md5() );
				}
			}
		}
	}

	/**
	 * Create unique md5 hash of this object depending on its attributes and the actual language.
	 *
	 * @return string
	 */
	protected function get_md5(): string {
		return md5( Languages::get_instance()->get_current_locale() . wp_json_encode( $this->get_config() ) );
	}

	/**
	 * Return the config of this seal as array.
	 *
	 * @return array
	 */
	protected function get_config(): array {
		return array();
	}
}
