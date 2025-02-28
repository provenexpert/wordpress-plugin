<?php
/**
 * File for handling logging in this plugin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Handler for logging in this plugin.
 */
class Log {
	/**
	 * Instance of this object.
	 *
	 * @var ?Log
	 */
	private static ?Log $instance = null;

	/**
	 * Constructor for this object.
	 */
	private function __construct() {}

	/**
	 * Prevent cloning of this object.
	 *
	 * @return void
	 */
	private function __clone() {}

	/**
	 * Return the instance of this Singleton object.
	 */
	public static function get_instance(): Log {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Create the logging-table in the database.
	 *
	 * @return void
	 */
	public function create_table(): void {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		// table for import-log.
		$sql = 'CREATE TABLE ' . $wpdb->prefix . "provenexpert_logs (
            `id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `time` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            `log` text DEFAULT '' NOT NULL,
            `md5` text DEFAULT '' NOT NULL,
            `category` varchar(40) DEFAULT '' NOT NULL,
            `state` varchar(40) DEFAULT '' NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Delete the logging-table in the database.
	 *
	 * @return void
	 */
	public function delete_table(): void {
		global $wpdb;
		$wpdb->query( sprintf( 'DROP TABLE IF EXISTS %s', esc_sql( $wpdb->prefix . 'provenexpert_logs' ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}

	/**
	 * Add a single log-entry.
	 *
	 * @param string $log   The text to log.
	 * @param string $state The state to log.
	 * @param string $category The category for this log entry (optional).
	 * @param string $md5 Marker to identify unique entries (optional).
	 *
	 * @return void
	 */
	public function add_log( string $log, string $state, string $category = '', string $md5 = '' ): void {
		// bail if state is info and debug is not enabled.
		if ( 'info' === $state && 1 !== absint( get_option( 'provenExpertDebug' ) ) ) {
			return;
		}

		global $wpdb;
		$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prefix . 'provenexpert_logs',
			array(
				'time'     => gmdate( 'Y-m-d H:i:s' ),
				'log'      => $log,
				'md5'      => $md5,
				'category' => $category,
				'state'    => $state,
			)
		);
		$this->clean_log();
	}

	/**
	 * Delete all entries which are older than X days.
	 *
	 * @return void
	 */
	public function clean_log(): void {
		// get db connection.
		global $wpdb;

		// run the deletion.
		$wpdb->query( sprintf( 'DELETE FROM %s WHERE `time` < DATE_SUB(NOW(), INTERVAL 7 DAY) LIMIT 10000', esc_sql( $wpdb->prefix . 'provenexpert_logs' ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}

	/**
	 * Return list of categories with internal name & its label.
	 *
	 * @return array
	 */
	public function get_categories(): array {
		$list = array(
			'system' => __( 'System', 'provenexpert' ),
		);

		/**
		 * Filter the list of possible log categories.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 *
		 * @param array $list List of categories.
		 */
		return apply_filters( 'provenexpert_log_categories', $list );
	}

	/**
	 * Get log entries depending on filter.
	 *
	 * Use for each possible condition own statements to match WCS.
	 *
	 * @return array
	 */
	public function get_entries(): array {
		global $wpdb;

		// order table.
		$order_by = filter_input( INPUT_GET, 'orderby', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( is_null( $order_by ) ) {
			$order_by = 'date';
		}
		$order = filter_input( INPUT_GET, 'order', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		if ( ! is_null( $order ) ) {
			$order = sanitize_sql_orderby( $order );
		} else {
			$order = 'DESC';
		}

		$limit = 10000;
		/**
		 * Filter limit to prevent possible errors on big tables.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param int $limit The actual limit.
		 */
		$limit = apply_filters( 'provenexpert_log_limit', $limit );

		// get filter.
		$category = filter_input( INPUT_GET, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// get md5.
		$md5 = filter_input( INPUT_GET, 'md5', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// if only category is set.
		if ( ! is_null( $category ) && is_null( $md5 ) ) {
			// get and return the entries.
			return $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->prepare(
					'SELECT `state`, `time` AS `date`, `log`, `category`
                    FROM `' . $wpdb->prefix . 'provenexpert_logs`
                    WHERE `category` = %s
                    ORDER BY ' . $order_by . ' ' . $order . '
                    LIMIT %d',
					array( $category, $limit )
				),
				ARRAY_A
			);
		}

		// if only md5 is set.
		if ( is_null( $category ) && ! is_null( $md5 ) ) {
			// get and return the entries.
			return $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->prepare(
					'SELECT `state`, `time` AS `date`, `log`, `category`
                    FROM `' . $wpdb->prefix . 'provenexpert_logs`
                    WHERE `md5` = %s
                    ORDER BY ' . $order_by . ' ' . $order . '
                    LIMIT %d',
					array( $md5, $limit )
				),
				ARRAY_A
			);
		}

		// if both are set.
		if ( ! is_null( $category ) && ! is_null( $md5 ) ) {
			// get and return the entries.
			return $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$wpdb->prepare(
					'SELECT `state`, `time` AS `date`, `log`, `category`
                    FROM `' . $wpdb->prefix . 'provenexpert_logs`
                    WHERE `md5` = %s AND `category` = %s
                    ORDER BY ' . $order_by . ' ' . $order . '
                    LIMIT %d',
					array( $md5, $category, $limit )
				),
				ARRAY_A
			);
		}

		// return all.
		return $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prepare(
				'SELECT `state`, `time` AS `date`, `log`, `category`
                FROM `' . $wpdb->prefix . 'provenexpert_logs`
                ORDER BY ' . $order_by . ' ' . $order . '
                LIMIT %d',
				array( $limit )
			),
			ARRAY_A
		);
	}

	/**
	 * Truncate the log table.
	 *
	 * @return void
	 */
	public function truncate_table(): void {
		// get db connection.
		global $wpdb;

		// run the deletion.
		$wpdb->query( sprintf( 'TRUNCATE TABLE %s ', esc_sql( $wpdb->prefix . 'provenexpert_logs' ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}
}
