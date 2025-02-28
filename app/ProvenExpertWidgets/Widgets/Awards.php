<?php
/**
 * File to handle the Awards Widget of ProvenExpert.
 *
 * @package provenexpert
 */

namespace ProvenExpert\ProvenExpertWidgets\Widgets;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Account\Account;
use ProvenExpert\Api\Api;
use ProvenExpert\ProvenExpertWidgets\Widget_Base;

/**
 * Object which represents this widget.
 */
class Awards extends Widget_Base {
	/**
	 * The public label.
	 *
	 * @var string
	 */
	protected string $label = 'Awards';

	/**
	 * The widget type.
	 *
	 * @var string
	 */
	protected string $type = 'awards';

	/**
	 * The widget width.
	 *
	 * @var int
	 */
	protected int $width = 100;

	/**
	 * If widget is fixed.
	 *
	 * @var int
	 */
	protected int $fixed = 0;

	/**
	 * The widget award type.
	 *
	 * @var string
	 */
	protected string $award_type = 'recommend';

	/**
	 * Return the config of this widget as array.
	 *
	 * @return array
	 */
	protected function get_config(): array {
		return array(
			'type'       => $this->get_type(),
			'width'      => $this->get_width(),
			'fixed'      => $this->get_fixed(),
			'origin'     => $this->get_origin(),
			'position'   => $this->get_position(),
			'award_type' => $this->get_award_type(),
		);
	}

	/**
	 * Return the HTML of this widget-object.
	 *
	 * @return string
	 */
	public function get_html(): string {
		// get API object.
		$api_obj = Api::get_instance();

		// bail if API is not prepared.
		if ( ! $api_obj->is_prepared() ) {
			return $api_obj->show_api_not_prepared();
		}

		$award_types = get_option( 'provenExpertWidget' . $this->get_md5(), '' );

		// if html is still empty, get it from API.
		if ( empty( $award_types ) ) {
			$this->update();
			$award_types = get_option( 'provenExpertWidget' . $this->get_md5(), '' );
		}

		// bail if configured award type is not available.
		if ( empty( $award_types[ $this->get_award_type() ] ) ) {
			return '';
		}

		// set the html-code of the chosen award type.
		$this->html = $award_types[ $this->get_award_type() ];

		// return the HTML-code of this award type.
		return parent::get_html();
	}

	/**
	 * Return the award type.
	 *
	 * @return string
	 */
	public function get_award_type(): string {
		return $this->award_type;
	}

	/**
	 * Set the award type.
	 *
	 * @param string $award_type The new award type.
	 *
	 * @return void
	 */
	public function set_award_type( string $award_type ): void {
		$this->award_type = $award_type;
	}

	/**
	 * Return whether this object is usable.
	 *
	 * @return bool
	 */
	public function is_usable(): bool {
		return Account::get_instance()->is_feature_enabled( 'awards' );
	}
}
