<?php
/**
 * File to handle plugin-settings.
 *
 * @package provenexpert
 */

namespace ProvenExpert\Plugin;

// prevent direct access.
defined( 'ABSPATH' ) || exit;

use ProvenExpert\Api\Api;
use ProvenExpert\ProvenExpertSeals\Seal_Base;
use ProvenExpert\ProvenExpertSeals\Seals;
use ProvenExpert\ProvenExpertWidgets\Widget_Base;
use ProvenExpert\ProvenExpertWidgets\Widgets;

/**
 * Object tot handle settings.
 */
class Settings {
	/**
	 * Instance of this object.
	 *
	 * @var ?Settings
	 */
	private static ?Settings $instance = null;

	/**
	 * Constructor for Settings-Handler.
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
	public static function get_instance(): Settings {
		if ( ! static::$instance instanceof static ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * Variable for complete settings.
	 *
	 * @var array
	 */
	private array $settings = array();

	/**
	 * Variable for tab settings.
	 *
	 * @var array
	 */
	private array $tabs = array();

	/**
	 * Initialize the settings.
	 *
	 * @return void
	 */
	public function init(): void {
		// set all settings for this plugin.
		add_action( 'init', array( $this, 'set_settings' ) );

		// register all settings for this plugin.
		add_action( 'init', array( $this, 'register_settings' ) );
		add_action( 'init', array( $this, 'register_additional_field_callbacks' ), PHP_INT_MAX );

		// register fields to manage the settings.
		add_action( 'admin_init', array( $this, 'register_fields' ) );
		add_action( 'admin_init', array( $this, 'register_field_callbacks' ) );
		add_action( 'rest_api_init', array( $this, 'register_field_callbacks' ) );

		// add admin-menu.
		add_action( 'admin_menu', array( $this, 'add_settings_menu' ) );
	}

	/**
	 * Define ALL settings for this plugin.
	 *
	 * @return void
	 */
	public function set_settings(): void {
		// set tabs.
		$this->tabs = array(
			array(
				'label'         => __( 'Settings', 'provenexpert' ),
				'key'           => '',
				'settings_page' => 'provenExpertMainSettings',
				'page'          => 'provenExpert',
				'order'         => 10,
				'do_not_save'   => true,
			),
			array(
				'label'         => __( 'Cache', 'provenexpert' ),
				'key'           => 'cache',
				'settings_page' => 'provenExpertCacheSettings',
				'page'          => 'provenExpert',
				'order'         => 30,
				'do_not_save'   => true,
			),
			array(
				'label'         => __( 'Advanced', 'provenexpert' ),
				'key'           => 'advanced',
				'settings_page' => 'provenExpertAdvancedSettings',
				'page'          => 'provenExpert',
				'order'         => 40,
			),
			array(
				'label'    => __( 'Logs', 'provenexpert' ),
				'key'      => 'logs',
				'callback' => array( 'ProvenExpert\Plugin\Admin\Logs', 'show' ),
				'page'     => 'provenExpert',
				'order'    => 50,
			),
			array(
				'label'      => __( 'Questions? Check our forum!', 'provenexpert' ),
				'key'        => 'help',
				'url'        => Helper::get_plugin_support_url(),
				'url_target' => '_blank',
				'class'      => 'nav-tab-help nav-tab-active',
				'page'       => 'provenExpert',
				'order'      => 2000,
			),
		);

		// define settings for this plugin.
		$this->settings = array(
			'settings_section_main'     => array(
				'label'         => __( 'API', 'provenexpert' ),
				'settings_page' => 'provenExpertMainSettings',
				'callback'      => array( $this, 'show_api_hint' ),
			),
			'settings_section_widgets'  => array(
				'label'         => __( 'ProvenExpert Widgets', 'provenexpert' ),
				'settings_page' => 'provenExpertMainSettings',
				'callback'      => array( $this, 'show_widget_hint' ),
				'fields'        => array(
					'provenExpertAvailableWidgets'    => array(
						'label'           => __( 'Usable widgets', 'provenexpert' ),
						'description'     => __( 'This is the list of widgets you will be able to your according to your ProvenExpert account.', 'provenexpert' ),
						'field'           => array( $this, 'show_available_widgets' ),
						'do_not_register' => true,
					),
					'provenExpertNotAvailableWidgets' => array(
						'label'           => __( 'Not available widgets', 'provenexpert' ),
						/* translators: %1$s will be replaced by an email. */
						'description'     => sprintf( __( 'This is the list of widgets you are <strong>not</strong> able to use. If you have any question about this <a href="mailto:%1$s">contact the ProvenExpert support</a>.', 'provenexpert' ), Helper::get_support_email() ),
						'field'           => array( $this, 'show_unavailable_widgets' ),
						'do_not_register' => true,
					),
				),
			),
			'settings_section_cache'    => array(
				'label'         => __( 'Cache', 'provenexpert' ),
				'settings_page' => 'provenExpertCacheSettings',
				'callback'      => '__return_true',
				'fields'        => array(
					'provenExpertUrlTimeout' => array(
						'label'           => __( 'Clear cache', 'provenexpert' ),
						'description'     => __( 'Clearing the cache reloads all widgets.<br><strong>Note:</strong> the cache is already emptied once a day.', 'provenexpert' ),
						'field'           => array( $this, 'show_reset_button' ),
						'do_not_register' => true,
					),
				),
			),
			'settings_section_advanced' => array(
				'label'         => __( 'Advanced settings', 'provenexpert' ),
				'settings_page' => 'provenExpertAdvancedSettings',
				'callback'      => '__return_true',
				'fields'        => array(
					'provenExpertUrlTimeout'        => array(
						'label'               => __( 'Timeout for URL-request in Seconds', 'provenexpert' ),
						'field'               => array( 'ProvenExpert\Plugin\Admin\SettingFields\Number', 'get' ),
						'register_attributes' => array(
							'sanitize_callback' => array( 'ProvenExpert\Plugin\Admin\SettingsValidation\UrlTimeout', 'validate' ),
							'type'              => 'integer',
							'default'           => 30,
						),
					),
					'provenExpertDeleteOnUninstall' => array(
						'label'               => __( 'Delete all imported data on uninstall', 'provenexpert' ),
						'field'               => array( 'ProvenExpert\Plugin\Admin\SettingFields\Checkbox', 'get' ),
						'register_attributes' => array(
							'type'    => 'integer',
							'default' => 1,
						),
					),
					'provenExpertDebug'             => array(
						'label'               => __( 'Enable debug mode', 'provenexpert' ),
						'field'               => array( 'ProvenExpert\Plugin\Admin\SettingFields\Checkbox', 'get' ),
						'register_attributes' => array(
							'type'    => 'integer',
							'default' => 0,
						),
					),
				),
			),
			'hidden_settings'           => array(
				'settings_page' => 'hidden_provenexpert_page',
				'fields'        => array(
					'provenExpertApiId'       => array(
						'register_attributes' => array(
							'default'      => '',
							'show_in_rest' => true,
							'type'         => 'string',
						),
					),
					'provenExpertApiKey'      => array(
						'register_attributes' => array(
							'default'      => '',
							'show_in_rest' => true,
							'type'         => 'string',
						),
					),
					'provenExpertWidgets'     => array(
						'register_attributes' => array(
							'type'    => 'array',
							'default' => array(),
						),
					),
					'provenExpertSeals'       => array(
						'register_attributes' => array(
							'type'    => 'array',
							'default' => array(),
						),
					),
					'provenExpertApiDisabled' => array(
						'register_attributes' => array(
							'type'    => 'integer',
							'default' => 0,
						),
					),
					'provenExpertPluginId'    => array(
						'register_attributes' => array(
							'type'    => 'string',
							'default' => '',
						),
					),
					'provenExpertAccount'     => array(
						'register_attributes' => array(
							'type'    => 'array',
							'default' => array(),
						),
					),
				),
			),
		);
	}

	/**
	 * Register the settings.
	 *
	 * @return void
	 */
	public function register_settings(): void {
		foreach ( $this->get_settings() as $section_settings ) {
			// bail if no fields are set.
			if ( empty( $section_settings['fields'] ) ) {
				continue;
			}

			// add each field.
			foreach ( $section_settings['fields'] as $field_name => $field_settings ) {
				if ( ! isset( $field_settings['do_not_register'] ) ) {
					$args = array();
					if ( ! empty( $field_settings['register_attributes'] ) ) {
						unset( $field_settings['register_attributes']['default'] );
						$args = $field_settings['register_attributes'];
					}
					register_setting(
						$section_settings['settings_page'],
						$field_name,
						$args
					);
					add_filter( 'option_' . $field_name, array( $this, 'sanitize_option' ), 10, 2 );
					add_filter( 'default_option_' . $field_name, array( $this, 'sanitize_option' ), 10, 2 );
				}
			}
		}
	}

	/**
	 * Register fields to manage the settings.
	 *
	 * @return void
	 */
	public function register_fields(): void {
		foreach ( $this->get_settings() as $section_name => $section_settings ) {
			if ( ! empty( $section_settings ) && ! empty( $section_settings['settings_page'] ) && ! empty( $section_settings['label'] ) && ! empty( $section_settings['callback'] ) ) {
				// bail if fields is empty and callback is just true.
				if ( empty( $section_settings['fields'] ) && '__return_true' === $section_settings['callback'] ) {
					continue;
				}

				$args = array();
				if ( isset( $section_settings['before_section'] ) ) {
					$args['before_section'] = $section_settings['before_section'];
				}
				if ( isset( $section_settings['after_section'] ) ) {
					$args['after_section'] = $section_settings['after_section'];
				}

				// add section.
				add_settings_section(
					$section_name,
					$section_settings['label'],
					$section_settings['callback'],
					$section_settings['settings_page'],
					$args
				);

				// bail if no fields are set.
				if ( empty( $section_settings['fields'] ) ) {
					continue;
				}

				// add fields in this section.
				foreach ( $section_settings['fields'] as $field_name => $field_settings ) {
					// get arguments for this field.
					$arguments = array(
						'label_for'         => $field_name,
						'fieldId'           => $field_name,
						'options'           => ! empty( $field_settings['options'] ) ? $field_settings['options'] : array(),
						'description'       => ! empty( $field_settings['description'] ) ? $field_settings['description'] : '',
						'placeholder'       => ! empty( $field_settings['placeholder'] ) ? $field_settings['placeholder'] : '',
						'readonly'          => ! empty( $field_settings['readonly'] ) ? $field_settings['readonly'] : false,
						'hide_empty_option' => ! empty( $field_settings['hide_empty_option'] ) ? $field_settings['hide_empty_option'] : false,
						'depends'           => ! empty( $field_settings['depends'] ) ? $field_settings['depends'] : array(),
						'class'             => ! empty( $field_settings['class'] ) ? $field_settings['class'] : array(),
					);

					/**
					 * Filter the arguments for this field.
					 *
					 * @since 1.0.0 Available since 1.0.0.
					 *
					 * @param array $arguments List of arguments.
					 * @param array $field_settings Setting for this field.
					 * @param string $field_name Internal name of the field.
					 */
					$arguments = apply_filters( 'provenexpert_setting_field_arguments', $arguments, $field_settings, $field_name );

					// add the field.
					add_settings_field(
						$field_name,
						$field_settings['label'],
						$field_settings['field'],
						$section_settings['settings_page'],
						$section_name,
						$arguments
					);
				}
			}
		}
	}

	/**
	 * Register field callbacks.
	 *
	 * @return void
	 */
	public function register_field_callbacks(): void {
		foreach ( $this->get_settings() as $section_settings ) {
			if ( ! empty( $section_settings ) && ! empty( $section_settings['settings_page'] ) ) {
				if ( ! empty( $section_settings['fields'] ) ) {
					foreach ( $section_settings['fields'] as $field_name => $field_settings ) {
						if ( ! empty( $field_settings['callback'] ) ) {
							add_filter( 'pre_update_option_' . $field_name, $field_settings['callback'], 10, 2 );
						}
					}
				}
			}
		}
	}

	/**
	 * Add settings-page for the plugin.
	 *
	 * @return void
	 */
	public function add_settings_menu(): void {
		// set title (will be used multiple times).
		$title = __( 'ProvenExpert', 'provenexpert' );

		// add menu entry for settings page.
		add_options_page(
			$title . ' ' . __( 'Settings', 'provenexpert' ),
			$title,
			'manage_options',
			'provenExpert',
			array( $this, 'add_settings_content' ),
		);
	}

	/**
	 * Create the admin-page with tab-navigation.
	 *
	 * @return void
	 */
	public function add_settings_content(): void {
		// check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// get the active tab from the request-param.
		$tab = sanitize_text_field( wp_unslash( filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS ) ) );

		// set page to show.
		$page = 'provenExpertMainSettings';

		// hide the save button.
		$hide_save_button = false;

		// set callback to use.
		$callback = '';

		// output wrapper.
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<nav class="nav-tab-wrapper">
				<?php
				foreach ( $this->get_tabs() as $tab_settings ) {
					// bail if tab-settings are not an array.
					if ( ! is_array( $tab_settings ) ) {
						continue;
					}

					// bail if tab-settings are not for the settings-page.
					if ( 'provenExpert' !== $tab_settings['page'] ) {
						continue;
					}

					// bail if tab should be hidden.
					if ( ! empty( $tab_settings['hidden'] ) ) {
						if ( $tab === $tab_settings['key'] ) {
							$page = $tab_settings['settings_page'];
						}
						continue;
					}

					// Set url.
					$url    = Helper::get_settings_url( 'provenExpert', $tab_settings['key'] );
					$target = '_self';
					if ( ! empty( $tab_settings['url'] ) ) {
						$url = $tab_settings['url'];
						if ( ! empty( $tab_settings['url_target'] ) ) {
							$target = $tab_settings['url_target'];
						}
					}

					// Set class for tab and page for form-view.
					$class = '';
					if ( ! empty( $tab_settings['class'] ) ) {
						$class .= ' ' . $tab_settings['class'];
					}
					if ( $tab === $tab_settings['key'] ) {
						$class .= ' nav-tab-active';
						if ( ! empty( $tab_settings['settings_page'] ) ) {
							$page = $tab_settings['settings_page'];
						}
						if ( ! empty( $tab_settings['callback'] ) ) {
							$callback = $tab_settings['callback'];
							$page     = '';
						}
						if ( isset( $tab_settings['do_not_save'] ) ) {
							$hide_save_button = $tab_settings['do_not_save'];
						}
					}

					// decide which tab-type we want to output.
					if ( isset( $tab_settings['do_not_link'] ) && false !== $tab_settings['do_not_link'] ) {
						?>
						<span class="nav-tab"><?php echo esc_html( $tab_settings['label'] ); ?></span>
						<?php
					} else {
						?>
						<a href="<?php echo esc_url( $url ); ?>" class="nav-tab<?php echo esc_attr( $class ); ?>" target="<?php echo esc_attr( $target ); ?>"><?php echo esc_html( $tab_settings['label'] ); ?></a>
						<?php
					}
				}
				?>
			</nav>

			<div class="tab-content">
			<?php
			if ( ! empty( $page ) ) {
				?>
					<form method="post" action="<?php echo esc_url( get_admin_url() ); ?>options.php" class="provenexpert-settings">
					<?php
					settings_fields( $page );
					do_settings_sections( $page );
					$hide_save_button ? '' : submit_button();
					?>
					</form>
					<?php
			}

			if ( ! empty( $callback ) ) {
				call_user_func( $callback );
			}
			?>
			</div>
		</div>
		<?php
	}

	/**
	 * Return the settings and save them on the object.
	 *
	 * @return array
	 */
	public function get_settings(): array {
		$settings = $this->settings;

		/**
		 * Filter the plugin-settings.
		 *
		 * @since 1.0.0 Available since 1.0.0
		 *
		 * @param array $settings The settings as array.
		 */
		$this->settings = apply_filters( 'provenexpert_settings', $settings );

		// return the resulting settings.
		return $this->settings;
	}

	/**
	 * Return the value of a single actual setting.
	 *
	 * @param string $setting The requested setting as string.
	 *
	 * @return string
	 */
	public function get_setting( string $setting ): string {
		return get_option( $setting );
	}

	/**
	 * Return settings for single field.
	 *
	 * @param string $field The requested fiel.
	 * @param array  $settings The settings to use.
	 *
	 * @return array
	 */
	public function get_settings_for_field( string $field, array $settings = array() ): array {
		foreach ( ( empty( $settings ) ? $this->get_settings() : $settings ) as $section_settings ) {
			// bail if no fields are set.
			if ( empty( $section_settings['fields'] ) ) {
				continue;
			}

			// check each field.
			foreach ( $section_settings['fields'] as $field_name => $field_settings ) {
				if ( $field === $field_name ) {
					return $field_settings;
				}
			}
		}

		// return empty array if no field has been found.
		return array();
	}

	/**
	 * Return the tabs for the settings page.
	 *
	 * @return array
	 */
	public function get_tabs(): array {
		$tabs = $this->tabs;
		/**
		 * Filter the list of tabs.
		 *
		 * @since 1.0.0 Available since 1.0.0
		 *
		 * @param array $false Set true to hide the buttons.
		 */
		$tabs = apply_filters( 'provenexpert_settings_tabs', $tabs );

		// sort them by 'order'-field.
		usort( $tabs, array( $this, 'sort_tabs' ) );

		// return resulting list of tabs.
		return $tabs;
	}

	/**
	 * Sort the tabs by 'order'-field.
	 *
	 * @param array $a Tab 1 to check.
	 * @param array $b Tab 2 to compare with tab 1.
	 *
	 * @return int
	 */
	public function sort_tabs( array $a, array $b ): int {
		if ( empty( $a['order'] ) ) {
			$a['order'] = 500;
		}
		if ( empty( $b['order'] ) ) {
			$b['order'] = 500;
		}
		return $a['order'] - $b['order'];
	}

	/**
	 * Initialize the options of this plugin, set its default values.
	 *
	 * Only used during installation.
	 *
	 * @return void
	 */
	public function initialize_options(): void {
		$this->set_settings();
		foreach ( $this->get_settings() as $section_settings ) {
			// bail if no fields are set.
			if ( empty( $section_settings['fields'] ) ) {
				continue;
			}

			// add each field.
			foreach ( $section_settings['fields'] as $field_name => $field_settings ) {
				if ( isset( $field_settings['register_attributes']['default'] ) && ! get_option( $field_name ) ) {
					add_option( $field_name, $field_settings['register_attributes']['default'], '', true );
				}
			}
		}
	}

	/**
	 * Sanitize our own option values before output.
	 *
	 * @param mixed  $value The value.
	 * @param string $option The option-name.
	 *
	 * @return mixed
	 */
	public function sanitize_option( mixed $value, string $option ): mixed {
		// get field settings.
		$field_settings = $this->get_settings_for_field( $option, $this->settings );

		// bail if no type is set.
		if ( empty( $field_settings['register_attributes']['type'] ) ) {
			return $value;
		}

		// if type is array, secure value for array.
		if ( 'array' === $field_settings['register_attributes']['type'] ) {
			// if it is an array, use it 1:1.
			if ( is_array( $value ) ) {
				return $value;
			}

			// return the secured value.
			return (array) $value;
		}

		// if type is int, secure value for int.
		if ( 'integer' === $field_settings['register_attributes']['type'] ) {
			return absint( $value );
		}

		// return the value.
		return $value;
	}

	/**
	 * Show button to clear the widget-cache.
	 *
	 * @param array $attributes The used attributes.
	 *
	 * @return void
	 */
	public function show_reset_button( array $attributes ): void {
		// create url.
		$url = add_query_arg(
			array(
				'action' => 'provenexpert_clear_cache',
				'nonce'  => wp_create_nonce( 'provenexpert-clear-cache' ),
			),
			get_admin_url() . 'admin.php'
		);

		// create dialog.
		$dialog = array(
			'title'   => __( 'Clear cache', 'provenexpert' ),
			'texts'   => array(
				'<p><strong>' . __( 'Are you sure you want to clear the cache?', 'provenexpert' ) . '</strong></p>',
			),
			'buttons' => array(
				array(
					'action'  => 'location.href="' . esc_url( $url ) . '";',
					'variant' => 'primary',
					'text'    => __( 'Yes, I want', 'provenexpert' ),
				),
				array(
					'action'  => 'closeDialog();',
					'variant' => 'secondary',
					'text'    => __( 'No', 'provenexpert' ),
				),
			),
		);
		echo '<a class="easy-dialog-for-wordpress button button-primary" data-dialog="' . esc_attr( wp_json_encode( $dialog ) ) . '">' . esc_html__( 'Execute', 'provenexpert' ) . '</a>';

		// show description, if set.
		if ( ! empty( $attributes['description'] ) ) {
			echo '<p>' . wp_kses_post( $attributes['description'] ) . '</p>';
		}
	}

	/**
	 * Register additional field callbacks.
	 *
	 * @return void
	 */
	public function register_additional_field_callbacks(): void {
		foreach ( $this->get_settings() as $section_settings ) {
			if ( ! empty( $section_settings ) && ! empty( $section_settings['settings_page'] ) && ! empty( $section_settings['label'] ) && ! empty( $section_settings['callback'] ) ) {
				if ( ! empty( $section_settings['fields'] ) ) {
					foreach ( $section_settings['fields'] as $field_name => $field_settings ) {
						if ( ! empty( $field_settings['read_callback'] ) ) {
							add_filter( 'option_' . $field_name, $field_settings['read_callback'] );
						}
					}
				}
			}
		}
	}

	/**
	 * Show hint with magic link if API is not configured.
	 *
	 * @return void
	 */
	public function show_api_hint(): void {
		// bail if API is configured => show disconnect button.
		if ( Api::get_instance()->is_prepared() ) {
			// create URL to disconnect.
			$url = add_query_arg(
				array(
					'action' => 'provenexpert_disconnect',
					'nonce'  => wp_create_nonce( 'provenexpert-disconnect' ),
				),
				get_admin_url() . 'admin.php'
			);

			// create dialog.
			$dialog = array(
				'title'   => __( 'Disconnect from ProvenExpert', 'provenexpert' ),
				'texts'   => array(
					'<p><strong>' . __( 'Are you sure you want to disconnect your WordPress website from ProvenExpert?', 'provenexpert' ) . '</strong></p>',
					'<p>' . __( 'You will no longer be able to use ProvenExpert widgets on your website.', 'provenexpert' ) . '</p>',
					'<p>' . __( 'All existing widgets will no longer be visible.', 'provenexpert' ) . '</p>',
				),
				'buttons' => array(
					array(
						'action'  => 'location.href="' . esc_url( $url ) . '";',
						'variant' => 'primary',
						'text'    => __( 'Yes, I want to disconnect', 'provenexpert' ),
					),
					array(
						'action'  => 'closeDialog();',
						'variant' => 'secondary',
						'text'    => __( 'No', 'provenexpert' ),
					),
				),
			);

			// show disconnect button.
			echo '<a href="" class="button button-primary easy-dialog-for-wordpress" data-dialog="' . esc_attr( wp_json_encode( $dialog ) ) . '">' . esc_html__( 'Disconnect from ProvenExpert', 'provenexpert' ) . '</a>';

			// do not run any more tasks here.
			return;
		}

		// create dialog.
		$dialog = array(
			'title'   => __( 'Connect with ProvenExpert', 'provenexpert' ),
			'texts'   => array(
				'<p><strong>' . __( 'After clicking on the button below you will be redirected to your ProvenExpert account.', 'provenexpert' ) . '</strong></p>',
				'<p>' . __( 'Click there on Administration > Integrations > WordPress and confirm the connection by clicking on the button there.', 'provenexpert' ) . '</strong></p>',
			),
			'buttons' => array(
				array(
					'action'  => 'location.href="' . esc_url( Setup::get_instance()->get_setup_link() ) . '";',
					'variant' => 'primary',
					'text'    => __( 'Yes, I want to connect', 'provenexpert' ),
				),
				array(
					'action'  => 'closeDialog();',
					'variant' => 'secondary',
					'text'    => __( 'No', 'provenexpert' ),
				),
			),
		);

		// show magic link to connect the plugin.
		echo '<a href="' . esc_url( Setup::get_instance()->get_setup_link() ) . '" class="button button-primary easy-dialog-for-wordpress" data-dialog="' . esc_attr( wp_json_encode( $dialog ) ) . '">' . esc_html__( 'Connect with ProvenExpert', 'provenexpert' ) . '</a>';
	}

	/**
	 * Return the widgets depending on actual ProvenExpert account settings.
	 *
	 * @param bool $is_usable True is we only request usable widgets, false if not.
	 *
	 * @return array
	 */
	private function get_widgets( bool $is_usable ): array {
		// get all widgets and seals in one list.
		$widgets = array_merge( Widgets::get_instance()->get_widgets(), Seals::get_instance()->get_seals() );

		// collect the list.
		$list = array();

		// loop through them.
		foreach ( $widgets as $widget_name ) {
			// bail if it is not a string.
			if ( ! is_string( $widget_name ) ) {
				continue;
			}

			// bail if the method get_instance is missing.
			if ( ! method_exists( $widget_name, 'get_instance' ) ) {
				continue;
			}

			// get the object.
			$obj = call_user_func( $widget_name . '::get_instance' );

			// bail if obj is not Widget_Base and not Seal_Base.
			if ( ! $obj instanceof Widget_Base && ! $obj instanceof Seal_Base ) {
				continue;
			}

			// bail if this object is not usable according to the account information we got from ProvenExpert.
			if ( $is_usable !== $obj->is_usable() ) {
				continue;
			}

			// get label.
			$label = $obj->get_label();

			// bail if label is not set.
			if ( empty( $label ) ) {
				continue;
			}

			$list[] = array(
				'label' => $label,
			);
		}

		// return the resulting list.
		return $list;
	}

	/**
	 * Show list of available widgets.
	 *
	 * @return void
	 */
	public function show_available_widgets(): void {
		// bail if Api is not connected.
		if ( ! Api::get_instance()->is_prepared() ) {
			echo '<p>' . esc_html__( 'Please connect your ProvenExpert account to this WordPress website first.', 'provenexpert' ) . '</p>';
			return;
		}

		// get the widgets.
		$list = $this->get_widgets( true );

		// bail if list is empty.
		if ( empty( $list ) ) {
			// show hint.
			/* translators: %1$s will be replaced by an email. */
			echo '<p>' . sprintf( wp_kses_post( 'Unfortunately, there are currently no widgets available. Please check the ProvenExpert package you have booked. If you have any questions, please <a href="mailto:%1$s">contact ProvenExpert support</a>.', 'provenexpert' ), esc_html( Helper::get_support_email() ) ) . '</p>';

			// create URL to check account data.
			$url = add_query_arg(
				array(
					'action' => 'provenexpert_check_account_data',
					'nonce'  => wp_create_nonce( 'provenexpert-check-account-data' ),
				),
				get_admin_url() . 'admin.php'
			);

			// show button to check profile.
			echo '<p><a href="' . esc_url( $url ) . '" class="button button-primary">' . esc_html__( 'Reload account data', 'provenexpert' ) . '</a></p>';

			// do nothing more.
			return;
		}

		// show the list.
		echo '<ul>';
		foreach ( $list as $item ) {
			echo '<li>' . esc_html( $item['label'] ) . '</li>';
		}
		echo '</ul>';
	}

	/**
	 * Show list of not available widgets.
	 *
	 * @return void
	 */
	public function show_unavailable_widgets(): void {
		// get the widgets.
		$list = $this->get_widgets( false );

		// bail if list is empty.
		if ( empty( $list ) ) {
			echo '<p>' . esc_html__( 'All widgets are currently available to you.', 'provenexpert' ) . '</p>';
			return;
		}

		// show the list.
		echo '<ul>';
		foreach ( $list as $item ) {
			echo '<li>' . esc_html( $item['label'] ) . '</li>';
		}
		echo '</ul>';
	}

	/**
	 * Show hint for the following widget list.
	 *
	 * @return void
	 */
	public function show_widget_hint(): void {
		/* translators: %1$s will be replaced by an email. */
		echo '<p>' . wp_kses_post( sprintf( __( 'Here you will find information on which ProvenExpert widgets you can use. This depends on the package you have booked with ProvenExpert. If you have any questions, please <a href="mailto:%1$s">contact ProvenExpert Support</a>.', 'provenexpert' ), esc_html( Helper::get_support_email() ) ) ) . '</p>';
	}
}
