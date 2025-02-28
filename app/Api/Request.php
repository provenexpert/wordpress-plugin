<?php
/**
 * File to handle single API request to ProvenExpert.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Api;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Plugin\Log;

/**
 * Object to handle single request.
 */
class Request {

	/**
	 * The API key.
	 *
	 * @var string
	 */
	private string $api_key = '';

	/**
	 * The API ID.
	 *
	 * @var string
	 */
	private string $api_id = '';

	/**
	 * The method to use.
	 *
	 * @var string
	 */
	private string $method = 'POST';

	/**
	 * The URL for the request.
	 *
	 * @var string
	 */
	private string $url;

	/**
	 * Set default http header.
	 *
	 * @var array
	 */
	private array $header = array();

	/**
	 * The HTTP-Post-data as array.
	 *
	 * @var array
	 */
	private array $post_data;

	/**
	 * The response.
	 *
	 * @var string
	 */
	private string $response;

	/**
	 * The http-status.
	 *
	 * @var int
	 */
	private int $http_status;

	/**
	 * JSON-encode the post data.
	 *
	 * @var bool
	 */
	private bool $post_data_json_encode = false;

	/**
	 * The md5 hash of the transferred object.
	 *
	 * @var string
	 */
	private string $md5 = '';

	/**
	 * Constructor to build this object.
	 */
	public function __construct() {}

	/**
	 * Set URL.
	 *
	 * @param string $url The url to request.
	 * @return void
	 */
	public function set_url( string $url ): void {
		$this->url = $url;
	}

	/**
	 * Set header for request additional to authentication-header which is set by this object.
	 *
	 * @param array $header List of headers.
	 * @return void
	 */
	public function set_header( array $header ): void {
		$this->header = $header;
	}

	/**
	 * Set post data for the request.
	 *
	 * @param array $post_data The post-data as array.
	 * @return void
	 */
	public function set_post_data( array $post_data ): void {
		$this->post_data = $post_data;
	}

	/**
	 * Send the request and collect the result in this object.
	 *
	 * Do not interpret anything of the response. This will be done by the requesting object.
	 *
	 * @return bool
	 */
	public function send(): bool {
		// merge header-array and create authentication string, if id and key are set.
		$headers                    = $this->header;
		$headers['Accept-Language'] = $this->get_language_for_header();

		if ( ! empty( $this->get_api_id() ) && ! empty( $this->get_api_key() ) ) {
			$headers = array_merge(
				$headers,
				array(
					'Authorization' => 'Basic ' . base64_encode( $this->get_api_id() . ':' . $this->get_api_key() ),
				)
			);
		}

		/**
		 * Filter the headers for the request.
		 *
		 * @since 1.0.0 Available since 1.0.0
		 *
		 * @param array $headers List of headers.
		 * @param Request $this The request-object.
		 */
		$headers = apply_filters( 'provenexpert_request_header', $headers, $this );

		// collect arguments for request.
		$args = array(
			'method'      => $this->get_method(),
			'headers'     => $headers,
			'httpversion' => '1.1',
			'timeout'     => 10,
			'redirection' => 10,
			'body'        => $this->get_post_data(),
		);

		$response = false;

		// send request and get the result-object.
		switch ( $this->get_method() ) {
			case 'GET':
				$response = wp_safe_remote_get( $this->get_url(), $args );
				break;
			case 'POST':
				if ( $this->get_post_data_json_encode() ) {
					$args['body'] = wp_json_encode( $args['body'] );
				}
				$response = wp_safe_remote_post( $this->get_url(), $args );
				break;
			case 'DELETE':
				$response = wp_safe_remote_get( $this->get_url(), $args );
				break;
		}

		// bail on error.
		if ( ! $response || is_wp_error( $response ) ) {
			// add event in log.
			Log::get_instance()->add_log( __( 'The request to the ProvenExpert API resulted in an error: ', 'provenexpert' ) . wp_json_encode( $response ), 'error', 'application', $this->get_md5() );

			// return false as request resulted in unspecific http error.
			return false;
		}

		// secure response.
		$this->response = wp_remote_retrieve_body( $response );

		// secure http-status.
		$this->http_status = absint( wp_remote_retrieve_response_code( $response ) );

		// if debug is enabled, log this request and response.
		if ( 1 === absint( get_option( 'provenExpertDebug' ) ) ) {
			// hide credentials.
			$args['headers']['Authorization'] = __( 'Hidden in log', 'provenexpert' );

			// check state.
			$state = 'info';
			if ( ! in_array( $this->get_http_status(), array( 200, 201, 204 ), true ) ) {
				$state = 'error';
			}

			// log event.
			/* translators: %1$s will be replaced by a URL, %2$s will be replaced by the request, %3$s by the response. */
			Log::get_instance()->add_log( sprintf( __( 'URL: %1$s<br><br>Request: %2$s<br><br>Response: %3$s<br><br>HTTP-Status: %4$s', 'provenexpert' ), '<code>' . esc_url( $this->get_url() ) . '</code>', '<code>' . wp_json_encode( $args ) . '</code>', '<code>' . esc_html( wp_json_encode( $this->get_response() ) ) . '</code>', '<code>' . $this->get_http_status() . '</code>' ), $state, 'api' );
		}

		// return true as request itself was successful.
		return true;
	}

	/**
	 * Return response of the request.
	 *
	 * @return string
	 */
	public function get_response(): string {
		return $this->response;
	}

	/**
	 * Return the http-status of this request.
	 *
	 * @return int
	 */
	public function get_http_status(): int {
		return $this->http_status;
	}

	/**
	 * Return the URL for the request.
	 *
	 * @return string
	 */
	private function get_url(): string {
		return $this->url;
	}

	/**
	 * Return the POST-data.
	 *
	 * @return array
	 */
	public function get_post_data(): array {
		return $this->post_data;
	}

	/**
	 * Set the API ID to use for this request.
	 *
	 * @param string $api_id The API ID.
	 *
	 * @return void
	 */
	public function set_api_id( string $api_id ): void {
		$this->api_id = $api_id;
	}

	/**
	 * Set the token to use for this request.
	 *
	 * @param string $api_key The key.
	 *
	 * @return void
	 */
	public function set_api_key( string $api_key ): void {
		$this->api_key = $api_key;
	}

	/**
	 * Return the Company ID.
	 *
	 * @return string
	 */
	private function get_api_id(): string {
		$api_id = $this->api_id;

		/**
		 * Filter the API ID the request is using.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 *
		 * @param string $api_id The API ID.
		 * @param Request $this The request-object.
		 */
		return apply_filters( 'provenexpert_request_api_id', $api_id, $this );
	}

	/**
	 * Return the token.
	 *
	 * @return string
	 */
	private function get_api_key(): string {
		$api_key = $this->api_key;

		/**
		 * Filter the API key the request is using.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 *
		 * @param string $api_key The API key.
		 * @param Request $this The request-object.
		 */
		return apply_filters( 'provenexpert_request_api_key', $api_key, $this );
	}

	/**
	 * Set md5 hash for the transferred object.
	 *
	 * @param string $md5 The md5 hash.
	 *
	 * @return void
	 */
	public function set_md5( string $md5 ): void {
		$this->md5 = $md5;
	}

	/**
	 * Return the md5 hash.
	 *
	 * @return string
	 */
	private function get_md5(): string {
		return $this->md5;
	}

	/**
	 * Generate the value for "Accept-Language"
	 *
	 * @return string
	 */
	private function get_language_for_header(): string {
		return substr( str_replace( '_', '-', strtolower( get_locale() ) ), 0, 5 );
	}

	/**
	 * Return the method to use.
	 *
	 * @return mixed
	 */
	private function get_method(): string {
		return $this->method;
	}

	/**
	 * Set method to use for this request.
	 *
	 * @param string $method The method (must be one of POST, GET or DELETE).
	 *
	 * @return void
	 */
	public function set_method( string $method ): void {
		if ( ! in_array( $method, array( 'POST', 'GET', 'DELETE' ), true ) ) {
			return;
		}
		$this->method = $method;
	}

	/**
	 * Set if post data should be JSON encoded.
	 *
	 * @param bool $enable Set true to enable this.
	 *
	 * @return void
	 */
	public function set_post_data_json_encode( bool $enable ): void {
		$this->post_data_json_encode = $enable;
	}

	/**
	 * Return whether post data should be encoded.
	 *
	 * @return bool
	 */
	private function get_post_data_json_encode(): bool {
		return $this->post_data_json_encode;
	}
}
