<?php
/**
 * File to handle every schedule in this plugin.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * The object which handles schedules.
 */
class Schedules {
	/**
	 * Instance of this object.
	 *
	 * @var ?Schedules
	 */
	private static ?Schedules $instance = null;

	/**
	 * Constructor for Schedules-Handler.
	 */
	private function __construct() {}

	/**
	 * Prevent cloning of this object.
	 *
	 * @return void
	 */
	private function __clone() { }

	/**
	 * Return the instance of this Singleton object.
	 */
	public static function get_instance(): Schedules {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Initialize all schedules of this plugin.
	 *
	 * @return void
	 */
	public function init(): void {
		// use our own hooks.
		if ( is_admin() ) {
			add_filter( 'provenexpert_schedule_our_events', array( $this, 'check_events' ) );
		}

		// loop through our own events.
		foreach ( $this->get_events() as $event ) {
			// get the schedule object.
			$schedule_obj = $this->get_schedule_object_by_name( $event['name'] );
			if ( $schedule_obj instanceof Schedules_Base ) {
				// set attributes in object, if available.
				if ( ! empty( $event['settings'][ array_key_first( $event['settings'] ) ]['args'] ) ) {
					$schedule_obj->set_args( $event['settings'][ array_key_first( $event['settings'] ) ]['args'] );
				}

				// define action hook to run the schedule.
				add_action( $schedule_obj->get_name(), array( $schedule_obj, 'run' ), 10, 0 );
			}
		}

		// action to create all registered schedules.
		add_filter( 'schedule_event', array( $this, 'add_schedule_to_list' ) );
		add_action( 'shutdown', array( $this, 'check_events_on_shutdown' ) );
	}

	/**
	 * Get our own active events from WP-list.
	 *
	 * @return array
	 */
	private function get_events(): array {
		// get our own events from events list in WordPress.
		$our_events = $this->get_wp_events();

		/**
		 * Filter the list of our own events,
		 * e.g. to check if all which are enabled in setting are active.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 *
		 * @param array $our_events List of our own events in WP-cron.
		 */
		return apply_filters( 'provenexpert_schedule_our_events', $our_events );
	}

	/**
	 * Check the available events with the ones which should be active.
	 *
	 * Re-installs missing events. Log this event.
	 *
	 * Does only run in wp-admin, not frontend.
	 *
	 * @param array $our_events List of our own events.
	 *
	 * @return array
	 */
	public function check_events( array $our_events ): array {
		// bail if check should be disabled.
		$false = false;
		/**
		 * Disable the additional cron check.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 * @param bool $false True if check should be disabled.
		 *
		 * @noinspection PhpConditionAlreadyCheckedInspection
		 */
		if ( apply_filters( 'provenexpert_disable_cron_check', $false ) ) {
			return $our_events;
		}

		// bail if plugin activation is running.
		if ( defined( 'PROVENEXPERT_ACTIVATION_RUNNING' ) ) {
			return $our_events;
		}

		// check the schedule objects if they are set.
		foreach ( $this->get_schedule_object_names() as $object_name ) {
			// get the object.
			$obj = new $object_name();

			// bail if object is not from Schedules_Base.
			if ( ! $obj instanceof Schedules_Base ) {
				continue;
			}

			// install if schedule is enabled and not in list of our schedules.
			if ( $obj->is_enabled() && ! isset( $our_events[ $obj->get_name() ] ) ) {
				// reinstall the missing event.
				$obj->install();

				// log this event if debug-mode is enabled.
				if ( 1 === absint( get_option( 'provenExpertDebug' ) ) ) {
					/* translators: %1$s will be replaced by the event name. */
					Log::get_instance()->add_log( sprintf( __( 'Missing cron event <i>%1$s</i> automatically re-installed.', 'provenexpert' ), esc_html( $obj->get_name() ) ), 'success', $obj->get_log_category() );
				}

				// re-run the check for WP-cron-events.
				$our_events = $this->get_wp_events();
			}

			// delete if schedule is in list of our events and not enabled.
			if ( ! $obj->is_enabled() && isset( $our_events[ $obj->get_name() ] ) ) {
				$obj->delete();

				// log this event if debug-mode is enabled.
				if ( 1 === absint( get_option( 'provenExpertDebug' ) ) ) {
					/* translators: %1$s will be replaced by the event name. */
					Log::get_instance()->add_log( sprintf( __( 'Not enabled cron event <i>%1$s</i> automatically removed.', 'provenexpert' ), esc_html( $obj->get_name() ) ), 'success', $obj->get_log_category() );
				}

				// re-run the check for WP-cron-events.
				$our_events = $this->get_wp_events();
			}
		}

		// return resulting list.
		return $our_events;
	}

	/**
	 * Delete all our registered schedules.
	 *
	 * @return void
	 */
	public function delete_all(): void {
		foreach ( $this->get_schedule_object_names() as $obj_name ) {
			$schedule_obj = new $obj_name();
			if ( $schedule_obj instanceof Schedules_Base ) {
				$schedule_obj->delete();
			}
		}
	}

	/**
	 * Create our schedules per request.
	 *
	 * @return void
	 */
	public function create_schedules(): void {
		// install the schedules if they do not exist atm.
		foreach ( $this->get_schedule_object_names() as $obj_name ) {
			$schedule_obj = new $obj_name();
			if ( $schedule_obj instanceof Schedules_Base ) {
				$schedule_obj->install();
			}
		}
	}

	/**
	 * Return list of all schedule-object-names.
	 *
	 * @return array
	 */
	public function get_schedule_object_names(): array {
		// list of schedules.
		$list_of_schedules = array(
			'\ProvenExpert\Plugin\Schedules\CheckAccount',
			'\ProvenExpert\Plugin\Schedules\ResetSeals',
			'\ProvenExpert\Plugin\Schedules\ResetWidgets',
		);

		/**
		 * Add custom schedule-objects to use.
		 *
		 * This must be objects based on ProvenExpert\Plugin\Schedules_Base.
		 *
		 * @since 1.0.0 Available since 1.0.0.
		 *
		 * @param array $list_of_schedules List of additional schedules.
		 */
		return apply_filters( 'provenexpert_schedules', $list_of_schedules );
	}

	/**
	 * Get schedule object by its name.
	 *
	 * @param string $name The name of the object.
	 *
	 * @return false|Schedules_Base
	 */
	private function get_schedule_object_by_name( string $name ): false|Schedules_Base {
		foreach ( $this->get_schedule_object_names() as $object_name ) {
			$obj = new $object_name();
			if ( $obj instanceof Schedules_Base && $name === $obj->get_name() ) {
				return $obj;
			}
		}
		return false;
	}

	/**
	 * Get our own events from WP-cron-event-list.
	 *
	 * @return array
	 */
	private function get_wp_events(): array {
		$our_events = array();
		foreach ( _get_cron_array() as $events ) {
			foreach ( $events as $event_name => $event_settings ) {
				if ( str_contains( $event_name, 'provenexpert' ) ) {
					$our_events[ $event_name ] = array(
						'name'     => $event_name,
						'settings' => $event_settings,
					);
				}
			}
		}

		// return resulting list.
		return $our_events;
	}

	/**
	 * Run check for cronjobs in frontend, if enabled.
	 *
	 * @return void
	 */
	public function check_events_on_shutdown(): void {
		$this->check_events( $this->get_events() );
	}

	/**
	 * Add schedule to our list of schedules.
	 *
	 * @param object|bool $event The event properties.
	 *
	 * @return object|bool
	 */
	public function add_schedule_to_list( object|bool $event ): object|bool {
		// bail if event is not an object.
		if ( ! is_object( $event ) ) {
			return $event;
		}

		// get our object.
		$schedule_obj = $this->get_schedule_object_by_name( $event->hook );

		// bail if this is not an event of our plugin.
		if ( ! $schedule_obj ) {
			return $event;
		}

		// get the actual list.
		$list = get_option( PROVENEXPERT_SCHEDULES );
		if ( ! is_array( $list ) ) {
			$list = array();
		}
		$list[ $schedule_obj->get_name() ] = $schedule_obj->get_args();
		update_option( PROVENEXPERT_SCHEDULES, $list );

		// return the event object.
		return $event;
	}
}
