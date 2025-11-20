# Hooks

- [Actions](#actions)
- [Filters](#filters)

## Actions

*This project does not contain any WordPress actions.*

## Filters

### `provenexpert_provenexpert_seals`

*Filter the possible seals.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$seals` | `array<int,string>` | List of the seals.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/ProvenExpertSeals/Seals.php](ProvenExpertSeals/Seals.php), [line 57](ProvenExpertSeals/Seals.php#L57-L63)

### `provenexpert_current_language`

*Filter the resulting language.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$wp_language` | `string` | The language-name (e.g. "en").

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Languages.php](Plugin/Languages.php), [line 77](Plugin/Languages.php#L77-L84)

### `provenexpert_current_locale`

*Filter the resulting language.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$locale` | `string` | The language-name (e.g. "en-us").

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Languages.php](Plugin/Languages.php), [line 99](Plugin/Languages.php#L99-L106)

### `provenexpert_crypt_methods`

*Filter the available crypt-methods.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$methods` | `array<int,string>` | List of methods.

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
`$interface` | `\ProvenExpert\Plugin\Schedules_Base` | The schedule-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Schedules_Base.php](Plugin/Schedules_Base.php), [line 83](Plugin/Schedules_Base.php#L83-L90)

### `provenexpert_schedule_enabling`

*Filter whether to activate this schedule.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$false` | `bool` | True if this object should NOT be enabled.
`$interface` | `\ProvenExpert\Plugin\Schedules_Base` | Actual object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Schedules_Base.php](Plugin/Schedules_Base.php), [line 196](Plugin/Schedules_Base.php#L196-L206)

### `provenexpert_transient_hide_on`

*Filter where a single transient should be hidden.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$hide_on` | `array<int,string>` | List of absolute URLs.
`$interface` | `\ProvenExpert\Plugin\Transient` | The actual transient object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Transient.php](Plugin/Transient.php), [line 363](Plugin/Transient.php#L363-L371)

### `provenexpert_objects_with_db_tables`

*Add additional objects for this plugin which use custom tables.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$objects` | `array<int,string>` | List of objects.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Init.php](Plugin/Init.php), [line 127](Plugin/Init.php#L127-L133)

### `provenexpert_objects_with_db_tables`

*Add additional objects for this plugin which use custom tables.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$objects` | `array<int,string>` | List of objects.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Init.php](Plugin/Init.php), [line 165](Plugin/Init.php#L165-L171)

### `provenexpert_log_categories`

*Filter the list of possible log categories.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array<string,string>` | List of categories.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Log.php](Plugin/Log.php), [line 135](Plugin/Log.php#L135-L142)

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

Source: [app/Plugin/Log.php](Plugin/Log.php), [line 164](Plugin/Log.php#L164-L170)

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

Source: [app/Plugin/Settings.php](Plugin/Settings.php), [line 367](Plugin/Settings.php#L367-L376)

### `provenexpert_settings`

*Filter the plugin-settings.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$settings` | `array<string,mixed>` | The settings as array.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0

Source: [app/Plugin/Settings.php](Plugin/Settings.php), [line 550](Plugin/Settings.php#L550-L557)

### `provenexpert_settings_tabs`

*Filter the list of tabs.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$tabs` | `array` | Set true to hide the buttons.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0

Source: [app/Plugin/Settings.php](Plugin/Settings.php), [line 608](Plugin/Settings.php#L608-L615)

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

Source: [app/Plugin/Helper.php](Plugin/Helper.php), [line 185](Plugin/Helper.php#L185-L193)

### `provenexpert_log_table_filter`

*Filter the list before output.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array<string,string>` | List of filter.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Admin/Log_Table.php](Plugin/Admin/Log_Table.php), [line 176](Plugin/Admin/Log_Table.php#L176-L182)

### `provenexpert_do_not_decrypt`

*Do not decrypt a given value if requested.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$false` | `bool` | Return true to prevent decrypting.
`$value` | `string` | The requested value.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Admin/SettingsRead/GetDecryptValue.php](Plugin/Admin/SettingsRead/GetDecryptValue.php), [line 33](Plugin/Admin/SettingsRead/GetDecryptValue.php#L33-L43)

### `provenexpert_schedule_our_events`

*Filter the list of our own events.*

E.g. to check if all which are enabled in setting are active.

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$our_events` | `array<string,array<string,mixed>>` | List of our own events in WP-cron.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Schedules.php](Plugin/Schedules.php), [line 87](Plugin/Schedules.php#L87-L95)

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

Source: [app/Plugin/Schedules.php](Plugin/Schedules.php), [line 112](Plugin/Schedules.php#L112-L120)

### `provenexpert_schedules`

*Add custom schedule-objects to use.*

This must be objects based on ProvenExpert\Plugin\Schedules_Base.

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list_of_schedules` | `array<int,string>` | List of additional schedules.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Plugin/Schedules.php](Plugin/Schedules.php), [line 215](Plugin/Schedules.php#L215-L224)

### `provenexpert_shortcodes`

*Return list of shortcode class names.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array<int,string>` | List of shortcodes.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/Shortcodes/Shortcodes.php](PageBuilder/Shortcodes/Shortcodes.php), [line 54](PageBuilder/Shortcodes/Shortcodes.php#L54-L60)

### `provenexpert_shortcode_name`

*Filter the used shortcode name.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$name` | `string` | The name.
`$interface` | `\ProvenExpert\PageBuilder\Shortcodes\Shortcode_Base` | The shortcode-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/Shortcodes/Shortcode_Base.php](PageBuilder/Shortcodes/Shortcode_Base.php), [line 84](PageBuilder/Shortcodes/Shortcode_Base.php#L84-L91)

### `provenexpert_classic_widgets`

*Return list of classic widgets class names.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array<int,string>` | List of classic widgets.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/ClassicWidgets/ClassicWidgets.php](PageBuilder/ClassicWidgets/ClassicWidgets.php), [line 60](PageBuilder/ClassicWidgets/ClassicWidgets.php#L60-L66)

### `provenexpert_pagebuilder`

*Filter list of supported page builders.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$list` | `array<int,string>` | List of page builders.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/PageBuilders.php](PageBuilder/PageBuilders.php), [line 87](PageBuilder/PageBuilders.php#L87-L93)

### `provenexpert_block_editor_block_name`

*Filter the used block name.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$name` | `string` | The name.
`$instance` | `\ProvenExpert\PageBuilder\BlockEditor\Blocks_Base` | The block-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/PageBuilder/BlockEditor/Blocks_Base.php](PageBuilder/BlockEditor/Blocks_Base.php), [line 159](PageBuilder/BlockEditor/Blocks_Base.php#L159-L166)

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

Source: [app/PageBuilder/BlockEditor/BlockEditor.php](PageBuilder/BlockEditor/BlockEditor.php), [line 64](PageBuilder/BlockEditor/BlockEditor.php#L64-L70)

### `provenexpert_provenexpert_widgets`

*Filter the possible widgets.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$widgets` | `array<int,string>` | List of the widgets.

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
`$instance` | `\ProvenExpert\Api\Request` | The request-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0

Source: [app/Api/Request.php](Api/Request.php), [line 149](Api/Request.php#L149-L157)

### `provenexpert_request_api_id`

*Filter the API ID the request is using.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$api_id` | `string` | The API ID.
`$instance` | `\ProvenExpert\Api\Request` | The request-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Api/Request.php](Api/Request.php), [line 287](Api/Request.php#L287-L295)

### `provenexpert_request_api_key`

*Filter the API key the request is using.*

**Arguments**

Argument | Type | Description
-------- | ---- | -----------
`$api_key` | `string` | The API key.
`$instance` | `\ProvenExpert\Api\Request` | The request-object.

**Changelog**

Version | Description
------- | -----------
`1.0.0` | Available since 1.0.0.

Source: [app/Api/Request.php](Api/Request.php), [line 307](Api/Request.php#L307-L315)


<p align="center"><a href="https://github.com/pronamic/wp-documentor"><img src="https://cdn.jsdelivr.net/gh/pronamic/wp-documentor@main/logos/pronamic-wp-documentor.svgo-min.svg" alt="Pronamic WordPress Documentor" width="32" height="32"></a><br><em>Generated by <a href="https://github.com/pronamic/wp-documentor">Pronamic WordPress Documentor</a> <code>1.2.0</code></em><p>

