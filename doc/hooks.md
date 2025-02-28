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
`$seals` | `array` | List of the seals.

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

Source: [app/Plugin/Languages.php](Plugin/Languages.php), [line 102](Plugin/Languages.php#L102-L109)

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

Source: [app/Plugin/Languages.php](Plugin/Languages.php), [line 124](Plugin/Languages.php#L124-L131)

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

Source: [app/Plugin/Init.php](Plugin/Init.php), [line 140](Plugin/Init.php#L140-L146)

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

Source: [app/Plugin/Init.php](Plugin/Init.php), [line 163](Plugin/Init.php#L163-L169)

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

Source: [app/Plugin/Log.php](Plugin/Log.php), [line 134](Plugin/Log.php#L134-L141)

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

Source: [app/Plugin/Log.php](Plugin/Log.php), [line 167](Plugin/Log.php#L167-L173)

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

Source: [app/Plugin/Settings.php](Plugin/Settings.php), [line 344](Plugin/Settings.php#L344-L353)

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

Source: [app/Plugin/Settings.php](Plugin/Settings.php), [line 529](Plugin/Settings.php#L529-L536)

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

Source: [app/Plugin/Settings.php](Plugin/Settings.php), [line 587](Plugin/Settings.php#L587-L594)

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
`$list` | `array` | List of filter.

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

Source: [app/Plugin/Schedules.php](Plugin/Schedules.php), [line 214](Plugin/Schedules.php#L214-L223)

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

Source: [app/PageBuilder/Shortcodes/Shortcodes.php](PageBuilder/Shortcodes/Shortcodes.php), [line 54](PageBuilder/Shortcodes/Shortcodes.php#L54-L60)

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

Source: [app/PageBuilder/Shortcodes/Shortcode_Base.php](PageBuilder/Shortcodes/Shortcode_Base.php), [line 83](PageBuilder/Shortcodes/Shortcode_Base.php#L83-L90)

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

Source: [app/PageBuilder/ClassicWidgets/ClassicWidgets.php](PageBuilder/ClassicWidgets/ClassicWidgets.php), [line 60](PageBuilder/ClassicWidgets/ClassicWidgets.php#L60-L66)

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

Source: [app/PageBuilder/BlockEditor/Blocks_Base.php](PageBuilder/BlockEditor/Blocks_Base.php), [line 156](PageBuilder/BlockEditor/Blocks_Base.php#L156-L163)

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

Source: [app/Api/Request.php](Api/Request.php), [line 146](Api/Request.php#L146-L154)

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

Source: [app/Api/Request.php](Api/Request.php), [line 285](Api/Request.php#L285-L293)

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

Source: [app/Api/Request.php](Api/Request.php), [line 304](Api/Request.php#L304-L312)


<p align="center"><a href="https://github.com/pronamic/wp-documentor"><img src="https://cdn.jsdelivr.net/gh/pronamic/wp-documentor@main/logos/pronamic-wp-documentor.svgo-min.svg" alt="Pronamic WordPress Documentor" width="32" height="32"></a><br><em>Generated by <a href="https://github.com/pronamic/wp-documentor">Pronamic WordPress Documentor</a> <code>1.2.0</code></em><p>

