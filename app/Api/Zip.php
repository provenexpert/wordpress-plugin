<?php
/**
 * File with the object to handle a ZIP file from the API for a single widget.
 *
 * We handle this as follows:
 * 1. Request the ZIP-file from the API.
 * 2. Save the ZIP-file to a tmp file.
 * 3. Unzip the file in a custom directory in the WordPress-own upload directory.
 * 4. Save the HTML code from the JSON file in the plugin-own cache which will be used for the output of the widget.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Api;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use Error;
use ProvenExpert\Plugin\Helper;
use ProvenExpert\Plugin\Languages;
use ProvenExpert\Plugin\Log;
use ProvenExpert\ProvenExpertWidgets\Widgets;
use WP_Error;

/**
 * Object to handle a ZIP file from the API for a single widget.
 */
class Zip {
	/**
	 * The widget name for the ZIP-file.
	 *
	 * @var string
	 */
	private string $widget_name;

	/**
	 * Constructor for this object.
	 *
	 * @param string $widget_name The widget name for the ZIP-file.
	 */
	public function __construct( string $widget_name ) {
		$this->widget_name = $widget_name;
	}

	/**
	 * Return the widget name.
	 *
	 * @return string
	 */
	private function get_widget_name(): string {
		return $this->widget_name;
	}

	/**
	 * Run the ZIP-file request and processes the results.
	 *
	 * @return void
	 */
	public function run(): void {
		// get the API object.
		$api_obj = Api::get_instance();

		// request the ZIP-file.
		$request_obj = new Request();
		$request_obj->set_url( add_query_arg( array( 'widgets' => $this->get_widget_name() ), PROVENEXPERT_API_ZIP_URL ) );
		$request_obj->set_api_id( $api_obj->get_id() );
		$request_obj->set_api_key( $api_obj->get_key() );
		$request_obj->set_method( 'GET' );

		// send request to API.
		$request_obj->send();

		// bail if the result is not 200.
		if ( 200 !== $request_obj->get_http_status() ) {
			/* translators: %1$s: Widget name */
			Log::get_instance()->add_log( sprintf( __( 'Got faulty HTTP status from ProvenExpert API for request of ZIP-file for the widget %1$s:', 'provenexpert' ) . ' <code>' . $request_obj->get_http_status() . '</code>', $this->get_widget_name() ), 'error', 'api' );
			return;
		}

		// get the "WP_Filesystem" object.
		$wp_filesystem = Helper::get_wp_filesystem();

		// create a tmp file name.
		$tmp_file_name = wp_tempnam();

		// set the file as tmp-file for import with an appropriate file extension.
		$tmp_file = str_replace( '.tmp', '', $tmp_file_name . '.zip' );

		// delete the tmp file.
		$wp_filesystem->delete( $tmp_file_name );

		// get the response.
		$response = $request_obj->get_response();

		// save the response as a tmp file.
		try {
			$wp_filesystem->put_contents( $tmp_file, $response );
		} catch ( Error $e ) {
			/* translators: %1$s: Widget name */
			Log::get_instance()->add_log( sprintf( __( 'Could not save the ZIP-file for the widget %1$s to a temporary file. Following error occurred:', 'provenexpert' ) . ' <code>' . $e->getMessage() . '</code>', $this->get_widget_name() ), 'error', 'api' );

			// do nothing more.
			return;
		}

		// define the target directory for files of this widget.
		$target_dir = Widgets::get_instance()->get_upload_dir() . $this->get_widget_name() . '/';

		// create the target directory if it does not exist.
		if ( ! $wp_filesystem->exists( $target_dir ) ) {
			$wp_filesystem->mkdir( $target_dir );
		}

		// delete all files and directories in the target directory.
		$wp_filesystem->delete( $target_dir, true );

		// get the zip object in the target directory.
		$result = unzip_file( $tmp_file, $target_dir );

		// bail if unzipping failed.
		if ( $result instanceof WP_Error ) {
			/* translators: %1$s: Widget name */
			Log::get_instance()->add_log( sprintf( __( 'Could not unzip ZIP-file for the widget %1$s. Following error occurred:', 'provenexpert' ) . ' <code>' . Helper::get_json( $result ) . '</code>', $this->get_widget_name() ), 'error', 'api' );

			// do nothing more.
			return;
		}

		// get the HTML code from the JSON file in the response.
		$html      = '';
		$json_path = $target_dir . 'data/' . $this->get_widget_name() . '.json';
		if ( $wp_filesystem->exists( $json_path ) ) {
			$json  = (string) $wp_filesystem->get_contents( $json_path );
			$array = json_decode( $json, true );
			$html  = ! empty( $array['html'] ) ? $array['html'] : '';
		}

		// get the JS and CSS files from the directory.
		$js_file  = $target_dir . 'js/' . $this->get_widget_name() . '.js';
		$css_file = $target_dir . 'css/' . $this->get_widget_name() . '.css';

		// bail if anything does not exist.
		if ( empty( $html ) || ! $wp_filesystem->exists( $js_file ) ) {
			/* translators: %1$s: Widget name */
			Log::get_instance()->add_log( sprintf( __( 'Necessary files for the widget %1$s are missing.', 'provenexpert' ), $this->get_widget_name() ), 'error', 'api' );
			return;
		}

		// get the upload directory from WordPress.
		$upload_dir = wp_upload_dir();

		// get the URLs of the files.
		$js_url  = $upload_dir['baseurl'] . '/provenexpert-widgets/' . $this->get_widget_name() . '/js/' . $this->get_widget_name() . '.js';
		$css_url = $upload_dir['baseurl'] . '/provenexpert-widgets/' . $this->get_widget_name() . '/css/' . $this->get_widget_name() . '.css';

		// add the JS and CSS files to the HTML code.
		$html  = '<div class="provenexpert-widget-seal">' . $html . '</div>';
		$html .= '<script src="' . $js_url . '"></script>';
		if ( $wp_filesystem->exists( $css_file ) ) {
			$html .= '<link rel="stylesheet" href="' . $css_url . '">';
		}

		// add this widget to the list of "provenexpert_widgets".
		Widgets::get_instance()->add_widget_with_code( $html, $this->get_md5() );
	}

	/**
	 * Create a unique md5 hash of this object depending on its attributes and the actual language.
	 *
	 * @return string
	 */
	private function get_md5(): string {
		return md5( Languages::get_instance()->get_current_locale() . $this->get_widget_name() );
	}
}
