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
class Landing extends Widget_Base {
	/**
	 * The public label.
	 *
	 * @var string
	 */
	protected string $label = 'Landing';

	/**
	 * The widget type.
	 *
	 * @var string
	 */
	protected string $type = 'landing';

	/**
	 * The widget avatar.
	 *
	 * @var int
	 */
	protected int $avatar = 1;

	/**
	 * The widget avatar.
	 *
	 * @var int
	 */
	protected int $competence = 1;

	/**
	 * Return the config of this widget as array.
	 *
	 * @return array
	 */
	protected function get_config(): array {
		return array(
			'type'       => $this->get_type(),
			'style'      => $this->get_style(),
			'avatar'     => $this->get_avatar(),
			'feedback'   => $this->get_feedback(),
			'competence' => $this->get_competence(),
		);
	}

	/**
	 * Return the avatar.
	 *
	 * @return int
	 */
	public function get_avatar(): int {
		return $this->avatar;
	}

	/**
	 * Return the avatar.
	 *
	 * @param int $avatar The new avatar value.
	 *
	 * @return void
	 */
	public function set_avatar( int $avatar ): void {
		$this->avatar = $avatar;
	}

	/**
	 * Return the competence.
	 *
	 * @return int
	 */
	public function get_competence(): int {
		return $this->competence;
	}

	/**
	 * Return the competence.
	 *
	 * @param int $competence The new competence value.
	 *
	 * @return void
	 */
	public function set_competence( int $competence ): void {
		$this->avatar = $competence;
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
