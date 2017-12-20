<?php
/**
 * Theme Options.
 *
 * Here, you can register Theme Options using the Carbon Fields library.
 *
 * @see https://carbonfields.net/docs/containers-theme-options/
 */

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

Container::make( 'theme_options', __( 'Theme Options', 'wmpt' ) )
	->set_page_file( 'wmpt-theme-options.php' )
	->add_fields( array(
		Field::make( 'text', 'crb_google_maps_api_key', __( 'Google Maps API Key', 'wmpt' ) ),
		Field::make( 'header_scripts', 'crb_header_script', __( 'Header Script', 'wmpt' ) ),
		Field::make( 'footer_scripts', 'crb_footer_script', __( 'Footer Script', 'wmpt' ) ),
	) );
