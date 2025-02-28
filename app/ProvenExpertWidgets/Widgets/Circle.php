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
class Circle extends Widget_Base {
	/**
	 * The public label.
	 *
	 * @var string
	 */
	protected string $label = 'Circle';

	/**
	 * The widget type.
	 *
	 * @var string
	 */
	protected string $type = 'circle';

	/**
	 * The widget width.
	 *
	 * @var int
	 */
	protected int $width = 200;

	/**
	 * If widget is fixed.
	 *
	 * @var int
	 */
	protected int $fixed = 0;


	/**
	 * Return the config of this widget as array.
	 *
	 * @return array
	 */
	protected function get_config(): array {
		return array(
			'type'     => $this->get_type(),
			'width'    => $this->get_width(),
			'fixed'    => $this->get_fixed(),
			'origin'   => $this->get_origin(),
			'position' => $this->get_position(),
			'side'     => $this->get_side(),
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
