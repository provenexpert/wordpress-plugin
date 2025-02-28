<?php
/**
 * File for a classic widget for ProvenExpert seal ProSeal.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets_Trait;
use ProvenExpert\Plugin\Init;
use WP_Widget;

/**
 * Object to provide an old-fashion widget for ProvenExpert ProSeal.
 */
class ProSeal extends WP_Widget {
	use ClassicWidgets_Trait;

	/**
	 * Initialize this widget.
	 */
	public function __construct() {
		parent::__construct(
			'ProvenExpertClassicSealProSeal',
			__( 'ProvenExpert ProSeal', 'provenexpert' ),
			array(
				'description' => __( 'Provides a widget to show your ProvenExpert ProSeal.', 'provenexpert' ),
			)
		);
	}

	/**
	 * Get the fields for this widget.
	 *
	 * @return array[]
	 */
	private function get_fields(): array {
		// get the object for default values.
		$obj = \ProvenExpert\ProvenExpertSeals\Seals\ProSeal::get_instance();

		// return the field configuration.
		return array(
			'bannercolor'             => array(
				'type'    => 'color',
				'title'   => __( 'Banner color', 'provenexpert' ),
				'default' => $obj->get_banner_color(),
			),
			'textcolor'               => array(
				'type'    => 'color',
				'title'   => __( 'Text color', 'provenexpert' ),
				'default' => $obj->get_text_color(),
			),
			'showbackpage'            => array(
				'type'    => 'checkbox',
				'title'   => __( 'Show back page', 'provenexpert' ),
				'default' => $obj->get_show_back_page() ? 1 : 0,
			),
			'showreviews'             => array(
				'type'    => 'checkbox',
				'title'   => __( 'Show reviews', 'provenexpert' ),
				'default' => $obj->get_show_reviews() ? 1 : 0,
			),
			'hidedate'                => array(
				'type'    => 'checkbox',
				'title'   => __( 'Hide date', 'provenexpert' ),
				'default' => $obj->get_hide_date() ? 1 : 0,
			),
			'hidename'                => array(
				'type'    => 'checkbox',
				'title'   => __( 'Hide name', 'provenexpert' ),
				'default' => $obj->get_hide_name() ? 1 : 0,
			),
			'googlestars'             => array(
				'type'    => 'checkbox',
				'title'   => __( 'Google Stars', 'provenexpert' ),
				'default' => $obj->get_google_stars() ? 1 : 0,
			),
			'displayreviewerlastname' => array(
				'type'    => 'checkbox',
				'title'   => __( 'Display reviewer last name', 'provenexpert' ),
				'default' => $obj->get_display_reviewer_last_name() ? 1 : 0,
			),
			'bottom'                  => array(
				'type'    => 'number',
				'title'   => __( 'Bottom', 'provenexpert' ),
				'default' => $obj->get_bottom(),
			),
			'stickytoside'            => array(
				'type'   => 'select',
				'title'  => __( 'Sticky to side', 'provenexpert' ),
				'std'    => 'left',
				'values' => array(
					'left'  => __( 'Left', 'provenexpert' ),
					'right' => __( 'Right', 'provenexpert' ),
				),
			),
			'zindex'                  => array(
				'type'    => 'number',
				'title'   => __( 'Z-Index', 'provenexpert' ),
				'default' => $obj->get_z_index(),
			),
		);
	}

	/**
	 * Add form with settings for the widget.
	 *
	 * @param array $instance The instance of the widget.
	 *
	 * @return void
	 * @noinspection PhpMissingReturnTypeInspection
	 **/
	public function form( $instance ) {
		$this->create_widget_field_output( $this->get_fields(), $instance );
	}

	/**
	 * Save updated settings from the form.
	 *
	 * @param array $new_instance The new instance.
	 * @param array $old_instance The old instance.
	 * @return array
	 */
	public function update( $new_instance, $old_instance ): array {
		return $this->secure_widget_fields( $this->get_fields(), $new_instance, $old_instance );
	}

	/**
	 * Output of the widget in frontend.
	 *
	 * @param array $args List of arguments.
	 * @param array $settings List of settings.
	 *
	 * @return void
	 * @noinspection PhpParameterNameChangedDuringInheritanceInspection
	 * @noinspection DuplicatedCode
	 * @noinspection PhpMissingReturnTypeInspection
	 */
	public function widget( $args, $settings ) {
		// get the object.
		$obj = \ProvenExpert\ProvenExpertSeals\Seals\ProSeal::get_instance();

		// set the attributes, if given.
		if ( isset( $settings['bannercolor'] ) ) {
			$obj->set_banner_color( $settings['bannercolor'] );
		}
		if ( isset( $settings['textcolor'] ) ) {
			$obj->set_text_color( $settings['textcolor'] );
		}
		if ( isset( $settings['showbackpage'] ) ) {
			$obj->set_show_back_page( 1 === absint( $settings['showbackpage'] ) );
		}
		if ( isset( $settings['showreviews'] ) ) {
			$obj->set_show_reviews( 1 === absint( $settings['showreviews'] ) );
		}
		if ( isset( $settings['hidedate'] ) ) {
			$obj->set_hide_date( 1 === absint( $settings['hidedate'] ) );
		}
		if ( isset( $settings['hidename'] ) ) {
			$obj->set_hide_name( 1 === absint( $settings['hidename'] ) );
		}
		if ( isset( $settings['googlestars'] ) ) {
			$obj->set_google_stars( 1 === absint( $settings['googlestars'] ) );
		}
		if ( isset( $settings['displayreviewerlastname'] ) ) {
			$obj->set_display_reviewer_last_name( 1 === absint( $settings['displayreviewerlastname'] ) );
		}
		if ( isset( $settings['bottom'] ) ) {
			$obj->set_bottom( absint( $settings['bottom'] ) );
		}
		if ( isset( $settings['stickytoside'] ) ) {
			$obj->set_sticky_to_side( $settings['stickytoside'] );
		}
		if ( isset( $settings['zindex'] ) ) {
			$obj->set_z_index( absint( $settings['zindex'] ) );
		}

		// allow scripts.
		Init::get_instance()->prepare_kses();

		// display the resulting HTML-code from object.
		echo wp_kses_post( $obj->get_html() );
	}
}
