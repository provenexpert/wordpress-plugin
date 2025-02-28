<?php
/**
 * File to handle the schedule to check the account daily.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Schedules;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Account\Account;
use ProvenExpert\Plugin\Schedules_Base;

/**
 * Object for this schedule.
 */
class CheckAccount extends Schedules_Base {

	/**
	 * Name of this event.
	 *
	 * @var string
	 */
	protected string $name = 'provenexpert_check_account';

	/**
	 * Name of the option used to enable this event.
	 *
	 * @var string
	 */
	protected string $option_name = 'provenExpertEnableCheckAccount';

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
		$this->interval = 'daily';
	}

	/**
	 * Run this schedule.
	 *
	 * @return void
	 */
	public function run(): void {
		Account::get_instance()->check();
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
