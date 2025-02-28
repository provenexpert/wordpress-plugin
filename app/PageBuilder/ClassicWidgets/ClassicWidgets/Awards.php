<?php
/**
 * File for a classic widget for ProvenExpert award.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets_Trait;
use WP_Widget;

/**
 * Object to provide an old-fashion widget for ProvenExpert awards.
 */
class Awards extends WP_Widget {
	use ClassicWidgets_Trait;

	/**
	 * Initialize this widget.
	 */
	public function __construct() {
		parent::__construct(
			'ProvenExpertClassicWidgetAwards',
			__( 'ProvenExpert Awards', 'provenexpert' ),
			array(
				'description' => __( 'Provides a widget to show your ProvenExpert Awards.', 'provenexpert' ),
			)
		);
	}

	/**
	 * Get the fields for this widget.
	 *
	 * @return array[]
	 */
	private function get_fields(): array {
		// get the Seal widget object.
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Awards::get_instance();

		// return the configuration.
		return array(
			'award_type' => array(
				'type'   => 'select',
				'title'  => __( 'Award type', 'provenexpert' ),
				'std'    => $obj->get_award_type(),
				'values' => array(
					'recommend'    => __( 'Recommend', 'provenexpert' ),
					'topservice'   => __( 'Topservice', 'provenexpert' ),
					'toprecommend' => __( 'Top recommend', 'provenexpert' ),
				),
			),
			'width'      => array(
				'type'    => 'range',
				'title'   => __( 'Width', 'provenexpert' ),
				'min'     => 100,
				'max'     => 300,
				'default' => $obj->get_width(),
			),
			'fixed'      => array(
				'type'    => 'checkbox',
				'title'   => __( 'Dock seal on browser margin', 'provenexpert' ),
				'default' => absint( $obj->get_fixed() ),
			),
			'origin'     => array(
				'type'   => 'select',
				'title'  => __( 'Distance of seal measured from top or bottom browser margin?', 'provenexpert' ),
				'std'    => $obj->get_origin(),
				'values' => array(
					'top'    => __( 'Top', 'provenexpert' ),
					'bottom' => __( 'Bottom', 'provenexpert' ),
				),
			),
			'position'   => array(
				'type'    => 'range',
				'title'   => __( 'Position', 'provenexpert' ),
				'min'     => 0,
				'max'     => 1200,
				'default' => $obj->get_position(),
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
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Awards::get_instance();

		// set the attributes, if given.
		if ( isset( $settings['width'] ) ) {
			$obj->set_width( $settings['width'] );
		}
		if ( isset( $settings['fixed'] ) ) {
			$obj->set_fixed( $settings['fixed'] ? 1 : 0 );
		}
		if ( isset( $settings['origin'] ) ) {
			$obj->set_origin( $settings['origin'] );
		}
		if ( isset( $settings['position'] ) ) {
			$obj->set_position( $settings['position'] );
		}
		if ( isset( $settings['award_type'] ) ) {
			$obj->set_award_type( $settings['award_type'] );
		}

		// return the resulting HTML-code from object.
		echo wp_kses_post( $obj->get_html() );
	}
}
