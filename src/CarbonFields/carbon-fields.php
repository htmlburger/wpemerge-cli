<?php
/**
 * Load Carbon Fields.
 *
 * @package WPEmergeCli
 */

/**
 * Bootstrap Carbon Fields.
 */
function app_bootstrap_carbon_fields() {
	\Carbon_Fields\Carbon_Fields::boot();
}

/**
 * Bootstrap Carbon Fields container definitions.
 */
function app_bootstrap_carbon_fields_register_fields() {
	include_once APP_APP_SETUP_DIR . 'carbon-fields' . DIRECTORY_SEPARATOR . 'theme-options.php';
	include_once APP_APP_SETUP_DIR . 'carbon-fields' . DIRECTORY_SEPARATOR . 'post-meta.php';
	include_once APP_APP_SETUP_DIR . 'carbon-fields' . DIRECTORY_SEPARATOR . 'term-meta.php';
	include_once APP_APP_SETUP_DIR . 'carbon-fields' . DIRECTORY_SEPARATOR . 'user-meta.php';
}

/**
 * Filter Google Maps API key for Carbon Fields.
 */
function app_filter_carbon_fields_google_maps_api_key() {
	return carbon_get_theme_option( 'crb_google_maps_api_key' );
}
