<?php
/**
 * File to handle CLI tasks for this plugin.
 *
 * Call this via WP CLI on Bash.
 *
 * Examples:
 * - wp provenexpert delete_cache
 * - wp provenexpert validate
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Account\Account;
use ProvenExpert\Api\Api;
use ProvenExpert\ProvenExpertSeals\Seals;
use ProvenExpert\ProvenExpertWidgets\Widgets;

/**
 * Object with CLI tasks.
 */
class Cli {
	/**
	 * Delete all cached widgets (only their html codes).
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function delete_cache(): void {
		Widgets::get_instance()->delete_cache();
		Seals::get_instance()->delete_cache();
	}

	/**
	 * Reset plugin complete.
	 *
	 * @return void
	 * @noinspection PhpUnused
	 */
	public function reset_plugin(): void {
		Uninstaller::get_instance()->run();
		Installer::get_instance()->activation();
	}

	/**
	 * Check the account.
	 *
	 * @return void
	 */
	public function check(): void {
		Account::get_instance()->check();
	}

	/**
	 * Disconnect this plugin installation from ProvenExpert.
	 *
	 * @return void
	 */
	public function disconnect(): void {
		Api::get_instance()->disconnect();
	}
}
