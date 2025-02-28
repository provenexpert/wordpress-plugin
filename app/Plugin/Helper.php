<?php
/**
 * File with general helper tasks for the plugin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
use WP_Post;
use WP_Post_Type;

defined( 'ABSPATH' ) || exit;

/**
 * The helper class itself.
 */
class Helper {
	/**
	 * Get list of blogs in a multisite-installation.
	 *
	 * @return array
	 */
	public static function get_blogs(): array {
		if ( false === is_multisite() ) {
			return array();
		}

		// Get DB-connection.
		global $wpdb;

		// get blogs in this site-network.
		return $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			"
            SELECT blog_id
            FROM {$wpdb->blogs}
            WHERE site_id = '{$wpdb->siteid}'
            AND spam = '0'
            AND deleted = '0'
            AND archived = '0'
        "
		);
	}

	/**
	 * Return the plugin support url: the forum on WordPress.org.
	 *
	 * @return string
	 */
	public static function get_plugin_support_url(): string {
		return 'https://wordpress.org/support/plugin/provenexpert/';
	}

	/**
	 * Get language-specific ProvenExpert account login url.
	 *
	 * @return string
	 */
	public static function get_provenexpert_login_url(): string {
		return 'https://app.provenexpert.com/';
	}

	/**
	 * Get language-specific ProvenExpert account support url.
	 *
	 * @return string
	 */
	public static function get_provenexpert_support_url(): string {
		if ( Languages::get_instance()->is_german_language() ) {
			return 'https://www.provenexpert.com/de-de/kontakt/';
		}
		return 'https://www.provenexpert.com/en-us/contact/';
	}

	/**
	 * Return the URL where user can find his API credentials.
	 *
	 * @return string
	 */
	public static function get_provenexpert_api_page_url(): string {
		if ( Languages::get_instance()->is_german_language() ) {
			return 'https://www.provenexpert.com/de/personalisierte-umfragelinks/';
		}
		return 'https://www.provenexpert.com/en-us/custom-survey-links/';
	}

	/**
	 * Return the settings-URL.
	 *
	 * @param string $page The page to call (e.g. "provenExpert").
	 * @param string $tab String which represents the tab to link to.
	 *
	 * @return string
	 */
	public static function get_settings_url( string $page = 'provenExpert', string $tab = '' ): string {
		$params = array(
			'page' => $page,
		);
		if ( ! empty( $tab ) ) {
			$params['tab'] = $tab;
		}
		return add_query_arg( $params, get_admin_url() . 'options-general.php' );
	}

	/**
	 * Return the logo as img
	 *
	 * @return string
	 */
	public static function get_logo_img(): string {
		return '<img src="' . self::get_plugin_url() . 'gfx/provenexpert.png" alt="' . esc_attr_x( 'ProvenExpert Logo', 'Alt text for ProvenExpert logo', 'provenexpert' ) . '" class="logo">';
	}

	/**
	 * Return the absolute URL to the plugin (already trailed with slash).
	 *
	 * @return string
	 */
	public static function get_plugin_url(): string {
		return trailingslashit( plugin_dir_url( PROVENEXPERT_PLUGIN ) );
	}

	/**
	 * Return the absolute local filesystem-path (already trailed with slash) to the plugin.
	 *
	 * @return string
	 */
	public static function get_plugin_path(): string {
		return trailingslashit( plugin_dir_path( PROVENEXPERT_PLUGIN ) );
	}

	/**
	 * Format a given datetime with WP-settings and functions.
	 *
	 * @param string $date The date as YYYY-MM-DD.
	 * @return string
	 */
	public static function get_format_date_time( string $date ): string {
		$dt = get_date_from_gmt( $date );
		return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $dt ) );
	}

	/**
	 * Return the main proven expert URL.
	 *
	 * @return string
	 */
	public static function get_provenexpert_url(): string {
		return 'https://www.provenexpert.com';
	}

	/**
	 * Check if WP CLI has been called.
	 *
	 * @return bool
	 */
	public static function is_cli(): bool {
		return defined( 'WP_CLI' ) && WP_CLI;
	}

	/**
	 * Return the version of the given file.
	 *
	 * With WP_DEBUG or plugin-debug enabled its @filemtime().
	 * Without this it's the plugin-version.
	 *
	 * @param string $filepath The absolute path to the requested file.
	 *
	 * @return string
	 */
	public static function get_file_version( string $filepath ): string {
		// check for WP_DEBUG.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return filemtime( $filepath );
		}

		// check for own debug.
		if ( 1 === absint( get_option( 'provenExpertDebug', 0 ) ) ) {
			return filemtime( $filepath );
		}

		$plugin_version = PROVENEXPERT_VERSION;

		/**
		 * Filter the used file version (for JS- and CSS-files which get enqueued).
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 *
		 * @param string $plugin_version The plugin-version.
		 * @param string $filepath The absolute path to the requested file.
		 */
		return apply_filters( 'provenexpert_file_version', $plugin_version, $filepath );
	}

	/**
	 * Return the name of this plugin.
	 *
	 * @return string
	 */
	public static function get_plugin_name(): string {
		$plugin_data = get_plugin_data( PROVENEXPERT_PLUGIN );
		if ( ! empty( $plugin_data ) && ! empty( $plugin_data['Name'] ) ) {
			return $plugin_data['Name'];
		}
		return '';
	}

	/**
	 * Get current URL in frontend and backend.
	 *
	 * @return string
	 */
	public static function get_current_url(): string {
		if ( is_admin() && ! empty( $_SERVER['REQUEST_URI'] ) ) {
			return admin_url( basename( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) );
		}

		// set return value for page url.
		$page_url = '';

		// get actual object.
		$object = get_queried_object();
		if ( $object instanceof WP_Post_Type ) {
			$page_url = get_post_type_archive_link( $object->name );
		}
		if ( $object instanceof WP_Post ) {
			$page_url = get_permalink( $object->ID );
		}

		// return result.
		return $page_url;
	}

	/**
	 * Checks whether a given plugin is active.
	 *
	 * Used because WP's own function is_plugin_active() is not accessible everywhere.
	 *
	 * @param string $plugin Path to the requested plugin relative to plugin-directory.
	 * @return bool
	 */
	public static function is_plugin_active( string $plugin ): bool {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true );
	}

	/**
	 * Get backend URL of the post type with the most posts.
	 *
	 * @return string
	 */
	public static function get_url_of_post_type_with_post_posts(): string {
		$counters = array();
		foreach ( get_post_types( array( 'public' => true ) ) as $post_type_name ) {
			// skip attachments.
			if ( 'attachment' === $post_type_name ) {
				continue;
			}

			// get the count of posts of this post type.
			$counts = wp_count_posts( $post_type_name );

			// sum them.
			$counters[ $post_type_name ] = $counts->publish + $counts->future + $counts->draft + $counts->pending + $counts->private;
		}

		// bail if list is empty.
		if ( empty( $counters ) ) {
			// return the settings url instead.
			return self::get_settings_url();
		}

		// sort by most count of posts first.
		arsort( $counters );

		// get first entry.
		$post_name = array_key_first( $counters );

		// return the edit url of this post type.
		return add_query_arg( array( 'post_type' => $post_name ), get_admin_url() . 'edit.php' );
	}

	/**
	 * Return the URL where user can add their review.
	 *
	 * @return string
	 */
	public static function get_review_url(): string {
		return 'https://wordpress.org/support/plugin/provenexpert/#reviews';
	}

	/**
	 * Return the support email for ProvenExpert.
	 *
	 * @return string
	 */
	public static function get_support_email(): string {
		return 'support@provenexpert.com';
	}
}
