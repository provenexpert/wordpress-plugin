<?php
/**
 * File to handle the Circle Widget of ProvenExpert.
 *
 * @package provenexpert
 */

namespace ProvenExpert\ProvenExpertWidgets\Widgets;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Account\Account;
use ProvenExpert\ProvenExpertWidgets\Widget_Base;

/**
 * Object which represents this widget.
 */
class Bar extends Widget_Base {
	/**
	 * The public label.
	 *
	 * @var string
	 */
	protected string $label = 'Browser Bar';

	/**
	 * The widget type.
	 *
	 * @var string
	 */
	protected string $type = 'bar';

	/**
	 * Return the config of this widget as array.
	 *
	 * @return array
	 */
	protected function get_config(): array {
		return array(
			'type'     => $this->get_type(),
			'style'    => $this->get_style(),
			'feedback' => $this->get_feedback(),
		);
	}

	/**
	 * Return whether this object is usable.
	 *
	 * @return bool
	 */
	public function is_usable(): bool {
		return Account::get_instance()->is_feature_enabled( 'widget' );
	}
}
