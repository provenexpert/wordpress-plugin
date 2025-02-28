# Hooks

- [Actions](#actions)
- [Filters](#filters)

## Actions

*This project does not contain any WordPress actions.*

## Filters

### `provenexpert_crypt_methods`

*Filter the available crypt-methods.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$methods` | `array` | List of methods.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Crypt.php](Plugin/Crypt.php), [line 121](Plugin/Crypt.php#L121-L127)

### `provenexpert_get_transients_for_display`

*Filter the transients used and managed by this plugin.*

Hint: with help of this hook you could hide all transients this plugin is using. Simple return an empty array.

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$transients` | `array` | List of transients.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Transients.php](Plugin/Transients.php), [line 191](Plugin/Transients.php#L191-L200)

### `provenexpert_schedule_interval`

*Filter the interval for a single schedule.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$interval` | `string` | The interval.
`$this` | `\ProvenExpert\Plugin\Schedules_Base` | The schedule-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Schedules_Base.php](Plugin/Schedules_Base.php), [line 82](Plugin/Schedules_Base.php#L82-L89)

### `provenexpert_schedule_enabling`

*Filter whether to activate this schedule.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$false` | `bool` | True if this object should NOT be enabled.
`$this` | `\ProvenExpert\Plugin\Schedules_Base` | Actual object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Schedules_Base.php](Plugin/Schedules_Base.php), [line 194](Plugin/Schedules_Base.php#L194-L204)

### `provenexpert_transient_hide_on`

*Filter where a single transient should be hidden.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$hide_on` | `array` | List of absolute URLs.
`$this` | `\ProvenExpert\Plugin\Transient` | The actual transient object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Transient.php](Plugin/Transient.php), [line 366](Plugin/Transient.php#L366-L374)

### `provenexpert_objects_with_db_tables`

*Add additional objects for this plugin which use custom tables.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$objects` | `array` | List of objects.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Init.php](Plugin/Init.php), [line 113](Plugin/Init.php#L113-L119)

### `provenexpert_objects_with_db_tables`

*Add additional objects for this plugin which use custom tables.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$objects` | `array` | List of objects.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Init.php](Plugin/Init.php), [line 136](Plugin/Init.php#L136-L142)

### `provenexpert_log_categories`

*Filter the list of possible log categories.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array` | List of categories.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Log.php](Plugin/Log.php), [line 129](Plugin/Log.php#L129-L136)

### `provenexpert_log_limit`

*Filter limit to prevent possible errors on big tables.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$limit` | `int` | The actual limit.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Log.php](Plugin/Log.php), [line 162](Plugin/Log.php#L162-L168)

### `provenexpert_setting_field_arguments`

*Filter the arguments for this field.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$arguments` | `array` | List of arguments.
`$field_settings` | `array` | Setting for this field.
`$field_name` | `string` | Internal name of the field.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Settings.php](Plugin/Settings.php), [line 297](Plugin/Settings.php#L297-L306)

### `provenexpert_settings`

*Filter the plugin-settings.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$settings` | `array` | The settings as array.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0

Source: [app/Plugin/Settings.php](Plugin/Settings.php), [line 481](Plugin/Settings.php#L481-L488)

### `provenexpert_settings_tabs`

*Filter the list of tabs.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$tabs` |  | 

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0

Source: [app/Plugin/Settings.php](Plugin/Settings.php), [line 531](Plugin/Settings.php#L531-L538)

### `provenexpert_file_version`

*Filter the used file version (for JS- and CSS-files which get enqueued).*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$plugin_version` | `string` | The plugin-version.
`$filepath` | `string` | The absolute path to the requested file.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Helper.php](Plugin/Helper.php), [line 179](Plugin/Helper.php#L179-L187)

### `provenexpert_log_table_filter`

*Filter the list before output.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array` | List of filter.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Admin/Log_Table.php](Plugin/Admin/Log_Table.php), [line 176](Plugin/Admin/Log_Table.php#L176-L182)

### `provenexpert_do_not_encrypt`

*Do not encrypt a given value if requested.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$false` | `bool` | Return true to prevent decrypting.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Admin/SettingsSavings/Api.php](Plugin/Admin/SettingsSavings/Api.php), [line 33](Plugin/Admin/SettingsSavings/Api.php#L33-L42)

### `provenexpert_schedule_our_events`

*Filter the list of our own events,
e.g. to check if all which are enabled in setting are active.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$our_events` | `array` | List of our own events in WP-cron.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Schedules.php](Plugin/Schedules.php), [line 86](Plugin/Schedules.php#L86-L94)

### `provenexpert_disable_cron_check`

*Disable the additional cron check.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$false` | `bool` | True if check should be disabled.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Schedules.php](Plugin/Schedules.php), [line 111](Plugin/Schedules.php#L111-L119)

### `provenexpert_schedules`

*Add custom schedule-objects to use.*

This must be objects based on ProvenExpert\Plugin\Schedules_Base.

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list_of_schedules` | `array` | List of additional schedules.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Schedules.php](Plugin/Schedules.php), [line 207](Plugin/Schedules.php#L207-L216)

### `provenexpert_shortcodes`

*Return list of shortcode class names.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array` | List of shortcodes.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/Shortcodes/Shortcodes.php](PageBuilder/Shortcodes/Shortcodes.php), [line 53](PageBuilder/Shortcodes/Shortcodes.php#L53-L59)

### `provenexpert_shortcode_name`

*Filter the used shortcode name.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$name` | `string` | The name.
`$this` | `\ProvenExpert\PageBuilder\Shortcodes\Shortcode_Base` | The shortcode-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/Shortcodes/Shortcode_Base.php](PageBuilder/Shortcodes/Shortcode_Base.php), [line 74](PageBuilder/Shortcodes/Shortcode_Base.php#L74-L81)

### `provenexpert_classic_widgets`

*Return list of classic widgets class names.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array` | List of classic widgets.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/ClassicWidgets/ClassicWidgets.php](PageBuilder/ClassicWidgets/ClassicWidgets.php), [line 58](PageBuilder/ClassicWidgets/ClassicWidgets.php#L58-L64)

### `provenexpert_pagebuilder`

*Filter list of supported page builders.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array` | List of page builders.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/PageBuilders.php](PageBuilder/PageBuilders.php), [line 75](PageBuilder/PageBuilders.php#L75-L81)

### `provenexpert_block_editor_block_name`

*Filter the used block name.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$name` | `string` | The name.
`$this` | `\ProvenExpert\PageBuilder\Shortcodes\Shortcode_Base` | The block-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/BlockEditor/Blocks_Base.php](PageBuilder/BlockEditor/Blocks_Base.php), [line 150](PageBuilder/BlockEditor/Blocks_Base.php#L150-L157)

### `provenexpert_block_editor_blocks`

*Return list of block class names.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array` | List of blocks.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/BlockEditor/BlockEditor.php](PageBuilder/BlockEditor/BlockEditor.php), [line 63](PageBuilder/BlockEditor/BlockEditor.php#L63-L69)

### `provenexpert_provenexpert_widgets`

*Filter the possible widgets.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$widgets` | `array` | List of the widgets.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/ProvenExpertWidgets/Widgets.php](ProvenExpertWidgets/Widgets.php), [line 61](ProvenExpertWidgets/Widgets.php#L61-L67)

### `provenexpert_request_header`

*Filter the headers for the request.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$headers` | `array` | List of headers.
`$this` | `\ProvenExpert\Api\Request` | The request-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0

Source: [app/Api/Request.php](Api/Request.php), [line 126](Api/Request.php#L126-L134)

### `provenexpert_request_api_id`

*Filter the API ID the request is using.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$api_id` | `string` | The API ID.
`$this` | `\ProvenExpert\Api\Request` | The request-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Api/Request.php](Api/Request.php), [line 250](Api/Request.php#L250-L258)

### `provenexpert_request_api_key`

*Filter the API key the request is using.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$api_key` | `string` | The API key.
`$this` | `\ProvenExpert\Api\Request` | The request-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Api/Request.php](Api/Request.php), [line 269](Api/Request.php#L269-L277)


<p align="center"><a href="https://github.com/pronamic/wp-documentor"><img src="https://cdn.jsdelivr.net/gh/pronamic/wp-documentor@main/logos/pronamic-wp-documentor.svgo-min.svg" alt="Pronamic WordPress Documentor" width="32" height="32"></a><br><em>Generated by <a href="https://github.com/pronamic/wp-documentor">Pronamic WordPress Documentor</a> <code>1.2.0</code></em><p>

