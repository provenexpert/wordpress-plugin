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
class Number {

	/**
	 * Get the output.
	 *
	 * @param array $attributes The settings for this field.
	 *
	 * @return void
	 */
	public static function get( array $attributes ): void {
		if ( ! empty( $attributes['fieldId'] ) ) {
			// get value from config.
			$value = get_option( $attributes['fieldId'] );

			// get title.
			$title = '';
			if ( isset( $attributes['title'] ) ) {
				$title = $attributes['title'];
			}

			?>
			<input type="number" id="<?php echo esc_attr( $attributes['fieldId'] ); ?>" name="<?php echo esc_attr( $attributes['fieldId'] ); ?>" value="<?php echo esc_attr( $value ); ?>" class="provenexpert-field-width"<?php echo isset( $attributes['readonly'] ) && false !== $attributes['readonly'] ? ' disabled="disabled"' : ''; ?> title="<?php echo esc_attr( $title ); ?>" data-depends="<?php echo esc_attr( wp_json_encode( $attributes['depends'] ) ); ?>">
			<?php
			if ( ! empty( $attributes['description'] ) ) {
				echo '<p>' . wp_kses_post( $attributes['description'] ) . '</p>';
			}
		}
	}
}
