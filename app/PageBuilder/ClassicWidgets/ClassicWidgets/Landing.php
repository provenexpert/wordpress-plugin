<?php
/**
 * File for a classic widget for ProvenExpert widget landing.
 *
 * @package provenexpert
 */

namespace ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\PageBuilder\ClassicWidgets\ClassicWidgets_Trait;
use WP_Widget;

/**
 * Object to provide an old-fashion widget for ProvenExpert landing.
 */
class Landing extends WP_Widget {
	use ClassicWidgets_Trait;

	/**
	 * Initialize this widget.
	 */
	public function __construct() {
		parent::__construct(
			'ProvenExpertClassicWidgetLanding',
			__( 'ProvenExpert Evaluation Widget', 'provenexpert' ),
			array(
				'description' => __( 'Provides a widget to show your ProvenExpert Evaluation Widget.', 'provenexpert' ),
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
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Landing::get_instance();

		// return the configuration.
		return array(
			'style'      => array(
				'type'   => 'select',
				'title'  => __( 'Color of the header', 'provenexpert' ),
				'std'    => $obj->get_style(),
				'values' => array(
					'white' => __( 'White', 'provenexpert' ),
					'black' => __( 'Black', 'provenexpert' ),
				),
			),
			'feedback'   => array(
				'type'    => 'checkbox',
				'title'   => __( 'Display customer votes', 'provenexpert' ),
				'default' => absint( $obj->get_feedback() ),
			),
			'avatar'     => array(
				'type'    => 'checkbox',
				'title'   => __( 'Show profile image', 'provenexpert' ),
				'default' => absint( $obj->get_avatar() ),
			),
			'competence' => array(
				'type'    => 'checkbox',
				'title'   => __( 'Show top competencies', 'provenexpert' ),
				'default' => absint( $obj->get_competence() ),
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
	 * @noinspection PhpMissingReturnTypeInspection
	 */
	public function widget( $args, $settings ) {
		// get the object.
		$obj = \ProvenExpert\ProvenExpertWidgets\Widgets\Landing::get_instance();

		// set the attributes, if given.
		if ( isset( $settings['style'] ) ) {
			$obj->set_style( $settings['style'] );
		}
		if ( isset( $settings['feedback'] ) ) {
			$obj->set_feedback( $settings['feedback'] ? 1 : 0 );
		}
		if ( isset( $settings['avatar'] ) ) {
			$obj->set_avatar( $settings['avatar'] );
		}
		if ( isset( $settings['competence'] ) ) {
			$obj->set_competence( $settings['competence'] );
		}

		// return the resulting HTML-code from object.
		echo wp_kses_post( $obj->get_html() );
	}
}
