<?php
/**
 * File to handle a single text field for classic settings.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Admin\SettingFields;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Initialize the field.
 */
class Text {

	/**
	 * Get the output.
	 *
	 * @param array $attributes The settings for this field.
	 *
	 * @return void
	 */
	public static function get( array $attributes ): void {
		// check for nonce.
		if ( isset( $_GET['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'provenexpert-nonce' ) ) {
			return;
		}

		if ( ! empty( $attributes['fieldId'] ) ) {
			// get value from config.
			$value = get_option( $attributes['fieldId'] );

			// or get it from request.
			$request_value = sanitize_text_field( wp_unslash( filter_input( INPUT_POST, $attributes['fieldId'], FILTER_SANITIZE_FULL_SPECIAL_CHARS ) ) );
			if ( ! empty( $request_value ) ) {
				$value = $request_value;
			}

			// get title.
			$title = '';
			if ( isset( $attributes['title'] ) ) {
				$title = $attributes['title'];
			}

			// set readonly attribute.
			$readonly = '';
			if ( isset( $attributes['readonly'] ) && false !== $attributes['readonly'] ) {
				$readonly = ' disabled';
				?>
				<input type="hidden" name="<?php echo esc_attr( $attributes['fieldId'] ); ?>_ro" value="<?php echo esc_attr( $value ); ?>">
				<?php
			}

			// define depends array if it does not exist.
			if ( ! isset( $attributes['depends'] ) ) {
				$attributes['depends'] = array();
			}

			// output.
			?>
			<input type="text" id="<?php echo esc_attr( $attributes['fieldId'] ); ?>" name="<?php echo esc_attr( $attributes['fieldId'] ); ?>" value="<?php echo esc_attr( $value ); ?>"
				<?php
				echo ! empty( $attributes['placeholder'] ) ? ' placeholder="' . esc_attr( $attributes['placeholder'] ) . '"' : '';
				?>
				<?php echo esc_attr( $readonly ); ?> class="widefat" title="<?php echo esc_attr( $title ); ?>" data-depends="<?php echo esc_attr( wp_json_encode( $attributes['depends'] ) ); ?>">
			<?php
			if ( ! empty( $attributes['description'] ) ) {
				echo '<p>' . wp_kses_post( $attributes['description'] ) . '</p>';
			}
		}
	}
}
