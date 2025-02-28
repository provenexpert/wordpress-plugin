<?php
/**
 * File to handle the basic settings and functions for each Proven Expert widget.
 *
 * @source https://developer.provenexpert.com
 *
 * @package provenexpert
 */

namespace ProvenExpert\ProvenExpertWidgets;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Api\Api;
use ProvenExpert\Api\Request;
use ProvenExpert\Plugin\Helper;
use ProvenExpert\Plugin\Languages;
use ProvenExpert\Plugin\Log;
use ProvenExpert\Plugin\Object_Base;

/**
 * Object to handle the basic settings and functions for each Proven Expert widget.
 */
class Widget_Base extends Object_Base {

	/**
	 * The widgets type.
	 *
	 * @var string
	 */
	protected string $type = '';

	/**
	 * The width of the widget.
	 *
	 * @var int
	 */
	protected int $width = 0;

	/**
	 * The widgets fixed setting.
	 *
	 * @var int
	 */
	protected int $fixed = 0;

	/**
	 * The origin of the widget.
	 *
	 * @var string
	 */
	protected string $origin = 'top';

	/**
	 * The widgets position.
	 *
	 * @var int
	 */
	protected int $position = 0;

	/**
	 * The widgets side.
	 *
	 * @var string
	 */
	protected string $side = '';

	/**
	 * The widgets style.
	 *
	 * @var string
	 */
	protected string $style = '';

	/**
	 * The widget side.
	 *
	 * @var int
	 */
	protected int $feedback = 0;

	/**
	 * The HTML-code of this widget.
	 *
	 * @var string
	 */
	protected string $html = '';

	/**
	 * Instance of this object.
	 *
	 * @var ?Widget_Base
	 */
	private static ?Widget_Base $instance = null;

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
	public static function get_instance(): Widget_Base {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Return the type of this widget.
	 *
	 * @return string
	 */
	public function get_type(): string {
		return $this->type;
	}

	/**
	 * Return the HTML of this widget-object.
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
			$this->html = get_option( 'provenExpertWidget' . $this->get_md5(), '' );
		}

		// if html is still empty, get it from API.
		if ( empty( $this->html ) ) {
			$this->update();
			$this->html = get_option( 'provenExpertWidget' . $this->get_md5(), '' );
		}

		// return the resulting html code.
		return $this->html;
	}

	/**
	 * Update the cached HTML-code of this widget via request to the ProvenExpert API.
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

		// define the request.
		$request_obj = new Request();
		$request_obj->set_url( PROVENEXPERT_API_WIDGET_URL );
		$request_obj->set_api_id( $api_obj->get_id() );
		$request_obj->set_api_key( $api_obj->get_key() );
		$request_obj->set_post_data(
			array(
				'data' => $this->get_config(),
			)
		);

		// send request to API.
		$request_obj->send();

		// if result is 200, response is OK.
		if ( 200 === $request_obj->get_http_status() ) {
			// get the response.
			$response = $request_obj->get_response();

			// decode the response.
			$response_array = json_decode( $response, ARRAY_A );

			// bail if status is "error".
			if ( ! empty( $response_array['status'] ) && 'error' === $response_array['status'] && ! empty( $response_array['errors'] ) ) {
				// log event.
				Log::get_instance()->add_log( __( 'API-request resulted in error:', 'provenexpert' ) . ' <code>' . wp_json_encode( $response_array['errors'] ) . '</code>', 'error', 'api' );

				// if response contains "wrong credentials" clear the widget cache.
				if ( in_array( 'wrong credentials', $response_array['errors'], true ) ) {
					// log event.
					/* translators: %1$s will be replaced by the settings URL. */
					Log::get_instance()->add_log( sprintf( __( 'Your API credentials are wrong. You will not be able to use any widgets or seals. Check <a href="%1$s">your settings</a>.', 'provenexpert' ), esc_url( Helper::get_settings_url() ) ), 'error', 'api' );

					// disable the API.
					Api::get_instance()->set_disabled();

					// delete cache.
					Widgets::get_instance()->delete_cache();
				}

				// do nothing more.
				return;
			}

			// get the HTML-code from response array.
			if ( ! empty( $response_array['html'] ) ) {
				// save the response so widget can use it.
				update_option( 'provenExpertWidget' . $this->get_md5(), $response_array['html'] );

				// add this widget to the list of provenexpert_widgets.
				Widgets::get_instance()->add_widget_with_code( $this->get_md5() );
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
	 * Return whether this widget is fixed.
	 *
	 * @return int
	 */
	public function get_fixed(): int {
		return $this->fixed;
	}

	/**
	 * Set if the widget should be fixed.
	 *
	 * @param int $fixed The new width.
	 *
	 * @return void
	 */
	public function set_fixed( int $fixed ): void {
		$this->fixed = $fixed;
	}

	/**
	 * Return the type of this widget.
	 *
	 * @return int
	 */
	public function get_width(): int {
		return $this->width;
	}

	/**
	 * Set the width.
	 *
	 * @param int $width The new width.
	 *
	 * @return void
	 */
	public function set_width( int $width ): void {
		$this->width = $width;
	}

	/**
	 * Return the origin.
	 *
	 * @return string
	 */
	public function get_origin(): string {
		return $this->origin;
	}

	/**
	 * Set the origin.
	 *
	 * @param string $origin The new origin.
	 *
	 * @return void
	 */
	public function set_origin( string $origin ): void {
		$this->origin = $origin;
	}

	/**
	 * Return the position.
	 *
	 * @return int
	 */
	public function get_position(): int {
		return $this->position;
	}

	/**
	 * Set the position.
	 *
	 * @param int $position The new position.
	 *
	 * @return void
	 */
	public function set_position( int $position ): void {
		$this->position = $position;
	}

	/**
	 * Return the style.
	 *
	 * @return string
	 */
	public function get_style(): string {
		return $this->style;
	}

	/**
	 * Set the style.
	 *
	 * @param string $style The new style.
	 *
	 * @return void
	 */
	public function set_style( string $style ): void {
		$this->style = $style;
	}

	/**
	 * Return the feedback.
	 *
	 * @return string
	 */
	public function get_feedback(): string {
		return $this->feedback;
	}

	/**
	 * Set the feedback.
	 *
	 * @param int $feedback The new feedback.
	 *
	 * @return void
	 */
	public function set_feedback( int $feedback ): void {
		$this->feedback = $feedback;
	}

	/**
	 * Return the side.
	 *
	 * @return string
	 */
	public function get_side(): string {
		return $this->side;
	}

	/**
	 * Set the side.
	 *
	 * @param string $side The new side.
	 *
	 * @return void
	 */
	public function set_side( string $side ): void {
		$this->side = $side;
	}

	/**
	 * Return the config of this widget as array.
	 *
	 * @return array
	 */
	protected function get_config(): array {
		return array();
	}
}
