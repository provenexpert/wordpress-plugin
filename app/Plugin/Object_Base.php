<?php
/**
 * File to handle the basic object.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * The basic object.
 */
class Object_Base {
	/**
	 * The label.
	 *
	 * @var string
	 */
	protected string $label = '';

	/**
	 * Return the label for this object.
	 *
	 * @return string
	 */
	public function get_label(): string {
		return $this->label;
	}

	/**
	 * Return whether this object is usable. Defaults to false will prevent the usage.
	 *
	 * @return bool
	 */
	public function is_usable(): bool {
		return false;
	}

	/**
	 * Show error if widget is not usable.
	 *
	 * @return string
	 */
	public function show_not_usable(): string {
		// bail if user is not logged in.
		if ( ! is_user_logged_in() ) {
			return '';
		}

		return '<div class="provenexpert-hint"><p>' . __( 'Widget not activated for ProvenExpert account', 'provenexpert' ) . '</p><a href="' . esc_url( Helper::get_settings_url() ) . '" target="_blank"><span class="dashicons dashicons-editor-help"></span></a></div>';
	}
}
