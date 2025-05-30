<?php
/**
 * File to handle output of log table in admin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin\Admin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * Object to handle the output.
 */
class Logs {

	/**
	 * Output the table.
	 *
	 * @return void
	 */
	public static function show(): void {
		if ( current_user_can( 'manage_options' ) ) {
			// if WP_List_Table is not loaded automatically, we need to load it.
			if ( ! class_exists( 'WP_List_Table' ) ) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
			}
			$log = new Log_Table();
			$log->prepare_items();
			?>
			<div class="wrap">
				<h2><?php echo esc_html__( 'Logs', 'provenexpert' ); ?></h2>
				<?php
				$log->views();
				$log->display();
				?>
			</div>
			<?php
		}
	}
}
