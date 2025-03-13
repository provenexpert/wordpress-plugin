<?php
/**
 * File to collect all constants this plugin is using.
 *
 * @package provenexpert
 */

// prevent direct access.
defined( 'ABSPATH' ) || exit;

/**
 * The API URL of ProvenExpert for widgets.
 */
const PROVENEXPERT_API_WIDGET_URL = 'https://www.provenexpert.com/api/v1/widget/create';

/**
 * The API URL of ProvenExpert for seals.
 */
const PROVENEXPERT_API_SEAL_URL = 'https://www.provenexpert.com/restapi/v1/seals/';

/**
 * The API URL for any plugin tasks.
 */
const PROVENEXPERT_API_PLUGINS = 'https://www.provenexpert.com/restapi/v1/plugins/';

/**
 * The API URL for get infos about the used Provenexpert account.
 */
const PROVENEXPERT_API_ABOUT = 'https://www.provenexpert.com/restapi/v1/profiles/about';

/**
 * Name for field with transient names of this plugin.
 */
const PROVENEXPERT_TRANSIENTS_LIST = 'provenExpertTransients';

/**
 * Name of field with schedules of this plugin.
 */
const PROVENEXPERT_SCHEDULES = 'provenexpertSchedules';

/**
 * Name for the field where the installation hash for openssl is saved.
 */
const PROVENEXPERT_HASH = 'provenExpertHash';

/**
 * Name for the field where the installation hash for sodium is saved.
 */
const PROVENEXPERT_SODIUM_HASH = 'provenExpertSodiumHash';
