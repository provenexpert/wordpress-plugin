<?php
/**
 * File to handle the Awards Widget of ProvenExpert.
 *
 * @package provenexpert
 */

namespace ProvenExpert\ProvenExpertSeals\Seals;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Account\Account;
use ProvenExpert\Api\Api;
use ProvenExpert\Plugin\Languages;
use ProvenExpert\ProvenExpertSeals\Seal_Base;

/**
 * Object which represents this seal.
 */
class ProSeal extends Seal_Base {
	/**
	 * The public label.
	 *
	 * @var string
	 */
	protected string $label = 'ProSeal';

	/**
	 * The seal type.
	 *
	 * @var string
	 */
	protected string $type = 'proseal';

	/**
	 * Banner color.
	 *
	 * @var string
	 */
	private string $banner_color = '#000000';

	/**
	 * Text color.
	 *
	 * @var string
	 */
	private string $text_color = '#ffffff';

	/**
	 * Show page back.
	 *
	 * @var bool
	 */
	private bool $show_back_page = true;

	/**
	 * Show reviews.
	 *
	 * @var bool
	 */
	private bool $show_reviews = true;

	/**
	 * Hide date.
	 *
	 * @var bool
	 */
	private bool $hide_date = false;

	/**
	 * Hide name.
	 *
	 * @var bool
	 */
	private bool $hide_name = false;

	/**
	 * Google Stars.
	 *
	 * @var bool
	 */
	private bool $google_stars = false;

	/**
	 * Display reviewer last name.
	 *
	 * @var bool
	 */
	private bool $display_reviewer_last_name = false;

	/**
	 * Bottom.
	 *
	 * @var int
	 */
	private int $bottom = 130;

	/**
	 * Sticky to side.
	 *
	 * @var string
	 */
	private string $sticky_to_side = 'right';

	/**
	 * The z-index.
	 *
	 * @var int
	 */
	private int $z_index = 9999;

	/**
	 * Return the config of this seal as array.
	 *
	 * @return array
	 */
	protected function get_config(): array {
		return array(
			'language'                => $this->get_language(),
			'bannerColor'             => $this->get_banner_color(),
			'textColor'               => $this->get_text_color(),
			'showBackPage'            => $this->get_show_back_page(),
			'showReviews'             => $this->get_show_reviews(),
			'hideDate'                => $this->get_hide_date(),
			'hideName'                => $this->get_hide_name(),
			'googleStars'             => $this->get_google_stars(),
			'displayReviewerLastName' => $this->get_display_reviewer_last_name(),
			'bottom'                  => $this->get_bottom(),
			'stickyToSide'            => $this->get_sticky_to_side(),
			'zIndex'                  => $this->get_z_index(),
		);
	}

	/**
	 * Return the HTML of this seal-object.
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

		// get HTML-code from cache.
		$this->html = get_option( 'provenExpertSeal' . $this->get_md5(), '' );

		// if html is still empty, get it from API.
		if ( empty( $this->html ) ) {
			$this->update();
			$this->html = get_option( 'provenExpertSeal' . $this->get_md5(), '' );
		}

		// return the HTML-code of this award type.
		return parent::get_html();
	}

	/**
	 * Return setting for show back page.
	 *
	 * @return bool
	 */
	public function get_show_back_page(): bool {
		return $this->show_back_page;
	}

	/**
	 * Set setting for show back page.
	 *
	 * @param bool $show_back_page The value.
	 *
	 * @return void
	 */
	public function set_show_back_page( bool $show_back_page ): void {
		$this->show_back_page = $show_back_page;
	}

	/**
	 * Return setting for show reviews.
	 *
	 * @return bool
	 */
	public function get_show_reviews(): bool {
		return $this->show_reviews;
	}

	/**
	 * Set setting for show reviews.
	 *
	 * @param bool $show_reviews The value.
	 *
	 * @return void
	 */
	public function set_show_reviews( bool $show_reviews ): void {
		$this->show_reviews = $show_reviews;
	}

	/**
	 * Return setting for hide date.
	 *
	 * @return bool
	 */
	public function get_hide_date(): bool {
		return $this->hide_date;
	}

	/**
	 * Set setting for hide date.
	 *
	 * @param bool $hide_date The value.
	 *
	 * @return void
	 */
	public function set_hide_date( bool $hide_date ): void {
		$this->hide_date = $hide_date;
	}

	/**
	 * Return setting for hide name.
	 *
	 * @return bool
	 */
	public function get_hide_name(): bool {
		return $this->hide_name;
	}

	/**
	 * Set setting for hide name.
	 *
	 * @param bool $hide_name The value.
	 *
	 * @return void
	 */
	public function set_hide_name( bool $hide_name ): void {
		$this->hide_name = $hide_name;
	}

	/**
	 * Return setting for Google stars.
	 *
	 * @return bool
	 */
	public function get_google_stars(): bool {
		return $this->google_stars;
	}

	/**
	 * Set setting for Google stars.
	 *
	 * @param bool $google_stars The value.
	 *
	 * @return void
	 */
	public function set_google_stars( bool $google_stars ): void {
		$this->google_stars = $google_stars;
	}

	/**
	 * Return setting for display reviewer last name.
	 *
	 * @return bool
	 */
	public function get_display_reviewer_last_name(): bool {
		return $this->display_reviewer_last_name;
	}

	/**
	 * Set setting for display reviewer last name.
	 *
	 * @param bool $display_reviewer_last_name The value.
	 *
	 * @return void
	 */
	public function set_display_reviewer_last_name( bool $display_reviewer_last_name ): void {
		$this->display_reviewer_last_name = $display_reviewer_last_name;
	}

	/**
	 * Return setting for bottom.
	 *
	 * @return int
	 */
	public function get_bottom(): int {
		return $this->bottom;
	}

	/**
	 * Set setting for display reviewer last name.
	 *
	 * @param int $bottom The value.
	 *
	 * @return void
	 */
	public function set_bottom( int $bottom ): void {
		$this->bottom = $bottom;
	}

	/**
	 * Return setting for sticky to side.
	 *
	 * @return string
	 */
	private function get_sticky_to_side(): string {
		return $this->sticky_to_side;
	}

	/**
	 * Set setting for sticky to side.
	 *
	 * @param string $sticky_to_side The value.
	 *
	 * @return void
	 */
	public function set_sticky_to_side( string $sticky_to_side ): void {
		$this->sticky_to_side = $sticky_to_side;
	}

	/**
	 * Return setting for z-index.
	 *
	 * @return int
	 */
	public function get_z_index(): int {
		return $this->z_index;
	}

	/**
	 * Set setting for sticky to side.
	 *
	 * @param int $z_index The value.
	 *
	 * @return void
	 */
	public function set_z_index( int $z_index ): void {
		$this->z_index = $z_index;
	}

	/**
	 * Return setting for language.
	 *
	 * @return string
	 */
	private function get_language(): string {
		return Languages::get_instance()->get_current_locale();
	}

	/**
	 * Return setting for banner color.
	 *
	 * @return string
	 */
	public function get_banner_color(): string {
		return $this->banner_color;
	}

	/**
	 * Set banner color.
	 *
	 * @param string $banner_color The banner color.
	 *
	 * @return void
	 */
	public function set_banner_color( string $banner_color ): void {
		$this->banner_color = sanitize_hex_color( $banner_color );
	}

	/**
	 * Return setting for text color.
	 *
	 * @return string
	 */
	public function get_text_color(): string {
		return $this->text_color;
	}

	/**
	 * Set banner color.
	 *
	 * @param string $text_color The text color.
	 *
	 * @return void
	 */
	public function set_text_color( string $text_color ): void {
		$this->text_color = sanitize_hex_color( $text_color );
	}

	/**
	 * Return whether this object is usable.
	 *
	 * @return bool
	 */
	public function is_usable(): bool {
		return Account::get_instance()->is_feature_enabled( 'proSeal' );
	}
}
