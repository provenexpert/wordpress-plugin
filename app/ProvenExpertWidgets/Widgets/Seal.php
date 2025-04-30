<?php
/**
 * File to handle the Seal Widget of ProvenExpert.
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
class Seal extends Widget_Base {
	/**
	 * The public label.
	 *
	 * @var string
	 */
	protected string $label = 'Evaluation Seal';

	/**
	 * The widget type.
	 *
	 * @var string
	 */
	protected string $type = 'seal';

	/**
	 * The widget width.
	 *
	 * @var int
	 */
	protected int $width = 250;

	/**
	 * If widget is fixed.
	 *
	 * @var int
	 */
	protected int $fixed = 0;

	/**
	 * The widget slider setting.
	 *
	 * @var int
	 */
	protected int $slider = 0;

	/**
	 * The widget seal type setting.
	 *
	 * @var string
	 */
	protected string $seal_type = 'portrait';

	/**
	 * Return the config of this widget as array.
	 *
	 * @return array
	 */
	protected function get_config(): array {
		return array(
			'type'      => $this->get_type(),
			'width'     => $this->get_width(),
			'feedback'  => $this->get_feedback(),
			'slider'    => $this->get_slider(),
			'fixed'     => $this->get_fixed(),
			'origin'    => $this->get_origin(),
			'position'  => $this->get_position(),
			'side'      => $this->get_side(),
			'seal_type' => $this->get_seal_type(),
		);
	}

	/**
	 * Return the type of this widget.
	 *
	 * @return string
	 */
	public function get_type(): string {
		return $this->get_seal_type();
	}

	/**
	 * Return the slider setting.
	 *
	 * @return string
	 */
	private function get_seal_type(): string {
		return $this->seal_type;
	}

	/**
	 * Return the slider setting.
	 *
	 * @param string $seal_type The new seal type.
	 *
	 * @return void
	 */
	public function set_seal_type( string $seal_type ): void {
		$this->seal_type = $seal_type;
	}

	/**
	 * Return the slider setting.
	 *
	 * @return int
	 */
	public function get_slider(): int {
		return $this->slider;
	}

	/**
	 * Return the slider setting.
	 *
	 * @param int $slider The new slider.
	 *
	 * @return void
	 */
	public function set_slider( int $slider ): void {
		$this->slider = $slider;
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
