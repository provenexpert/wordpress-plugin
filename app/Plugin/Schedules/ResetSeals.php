<?php
/**
 * File to handle the schedule to reset the ProvenExpert seals cache daily.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Schedules;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Plugin\Schedules_Base;
use ProvenExpert\ProvenExpertSeals\Seals;

/**
 * Object for this schedule.
 */
class ResetSeals extends Schedules_Base {

	/**
	 * Name of this event.
	 *
	 * @var string
	 */
	protected string $name = 'provenexpert_reset_seals';

	/**
	 * Name of the option used to enable this event.
	 *
	 * @var string
	 */
	protected string $option_name = 'provenExpertEnableResetSeals';

	/**
	 * Name of the log category.
	 *
	 * @var string
	 */
	protected string $log_category = 'system';

	/**
	 * Initialize this schedule.
	 */
	public function __construct() {
		// get interval from settings.
		$this->interval = 'daily';
	}

	/**
	 * Run this schedule.
	 *
	 * @return void
	 */
	public function run(): void {
		Seals::get_instance()->delete_cache();
	}

	/**
	 * This schedule is enabled every time.
	 *
	 * @return bool
	 */
	public function is_enabled(): bool {
		return true;
	}
}
