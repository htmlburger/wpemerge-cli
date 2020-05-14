<?php

namespace MyApp\CarbonFields;

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;
use WPEmerge\ServiceProviders\ServiceProviderInterface;

/**
 * Provides Carbon Fields integration.
 */
class CarbonFieldsServiceProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( $container ) {
		// Nothing to register.
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		add_action( 'after_setup_theme', [$this, 'bootstrapCarbonFields'], 100 );
		add_filter( 'carbon_fields_map_field_api_key', [$this, 'filterCarbonFieldsGoogleMapsKey'] );
		add_action( 'carbon_fields_register_fields', [$this, 'registerFields'] );
		add_action( 'widgets_init', [$this, 'registerWidgets'] );
	}

	/**
	 * Bootstrap Carbon Fields.
	 *
	 * @return void
	 */
	public function bootstrapCarbonFields() {
		\Carbon_Fields\Carbon_Fields::boot();
	}

	/**
	 * Filter the Google Maps API key Carbon Fields use.
	 *
	 * @return string
	 */
	public function filterCarbonFieldsGoogleMapsKey() {
		return carbon_get_theme_option( 'crb_google_maps_api_key' );
	}

	/**
	 * Register Carbon Fields fields.
	 *
	 * @return void
	 */
	public function registerFields() {
		$this->registerThemeOptions();
		$this->registerPostMeta();
		$this->registerTermMeta();
		$this->registerUserMeta();
	}

	/**
	 * Register Theme Options fields.
	 *
	 * @return void
	 */
	protected function registerThemeOptions() {
		Container::make( 'theme_options', __( 'Theme Options', 'my_app' ) )
			->set_page_file( 'my_app-theme-options.php' )
			->add_fields( array(
				Field::make( 'text', 'crb_google_maps_api_key', __( 'Google Maps API Key', 'my_app' ) ),
				Field::make( 'header_scripts', 'crb_header_script', __( 'Header Script', 'my_app' ) ),
				Field::make( 'footer_scripts', 'crb_footer_script', __( 'Footer Script', 'my_app' ) ),
			) );
	}

	/**
	 * Register Post Meta fields.
	 *
	 * @return void
	 */
	protected function registerPostMeta() {
		/*
		Container::make( 'post_meta', __( 'Custom Data', 'my_app' ) )
			->where( 'post_type', '=', 'page' )
			->add_fields( array(
				Field::make( 'complex', 'crb_my_data' )
					->add_fields( array(
						Field::make( 'text', 'title' )
							->help_text( 'lorem' ),
					) ),
				Field::make( 'map', 'crb_location' )
					->set_position( 37.423156, -122.084917, 14 ),
				Field::make( 'image', 'crb_img' ),
				Field::make( 'file', 'crb_pdf' ),
			));
		*/
	}

	/**
	 * Register Term Meta fields.
	 *
	 * @return void
	 */
	protected function registerTermMeta() {
		/*
		Container::make( 'term_meta', __( 'Custom Data', 'my_app' ) )
			->where( 'term_taxonomy', '=', 'category' )
			->add_fields( array(
				Field::make( 'image', 'crb_img' ),
			));
		*/
	}

	/**
	 * Register User Meta fields.
	 *
	 * @return void
	 */
	protected function registerUserMeta() {
		/*
		Container::make( 'user_meta', __( 'Custom Data', 'my_app' ) )
			->add_fields( array(
				Field::make( 'image', 'crb_img' ),
			));
		*/
	}

	/**
	 * Register Widgets.
	 *
	 * @return void
	 */
	public function registerWidgets() {
		register_widget( \MyApp\Widgets\Carbon_Rich_Text_Widget::class );
	}
}
