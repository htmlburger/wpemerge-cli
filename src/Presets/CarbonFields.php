<?php

namespace WPEmerge\Cli\Presets;

class CarbonFields implements PresetInterface {
	use StatementAppenderTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Carbon Fields';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory ) {
		/**
		 * Copy setup files
		 */
		$source_dir = implode( DIRECTORY_SEPARATOR, [WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'carbon-fields'] ) . DIRECTORY_SEPARATOR;
		$destination_dir = implode( DIRECTORY_SEPARATOR, [$directory, 'app', 'setup', 'carbon-fields'] ) . DIRECTORY_SEPARATOR;

		if ( ! is_dir( $destination_dir ) ) {
			mkdir( $destination_dir );
		}

		$files = scandir( $source_dir );
		$files = array_filter( $files, function( $file ) {
			return preg_match( '~\.php$~', $file );
		} );

		foreach ( $files as $file ) {
			copy( $source_dir . $file, $destination_dir . $file );
		}

		/**
		 * Copy widget
		 */
		copy(
			implode( DIRECTORY_SEPARATOR, [WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'Carbon_Rich_Text_Widget.php'] ),
			implode( DIRECTORY_SEPARATOR, [$directory, 'app', 'src', 'Widgets', 'Carbon_Rich_Text_Widget.php'] )
		);

		/**
		 * Copy helper file
		 */
		copy(
			implode( DIRECTORY_SEPARATOR, [WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'carbon-fields.php'] ),
			implode( DIRECTORY_SEPARATOR, [$directory, 'app', 'helpers', 'carbon-fields.php'] )
		);

		/**
		 * Require helper file
		 */
		$helpers_filepath = implode( DIRECTORY_SEPARATOR, [$directory, 'app', 'helpers.php'] );

		$this->appendUniqueStatement(
			$helpers_filepath,
			'require_once WPMT_APP_HELPERS_DIR . \'carbon-fields.php\';'
		);

		/**
		 * Add hooks
		 */
		$hooks_filepath = implode( DIRECTORY_SEPARATOR, [$directory, 'app', 'hooks.php'] );

		$this->appendUniqueStatement(
			$hooks_filepath,
			'add_action( \'after_setup_theme\', \'wpmt_boot_carbon_fields\', 100 );'
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			'add_action( \'carbon_fields_register_fields\', \'wpmt_boot_carbon_fields_register_fields\' );'
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			'add_filter( \'carbon_fields_map_field_api_key\', \'wpmt_filter_carbon_fields_google_maps_api_key\' );'
		);

		return '';
	}
}
