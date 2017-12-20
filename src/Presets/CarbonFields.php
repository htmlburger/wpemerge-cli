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
		 * Copy helper file
		 */
		copy(
			implode( DIRECTORY_SEPARATOR, [WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'carbon-fields.php'] ),
			implode( DIRECTORY_SEPARATOR, [$directory, 'app', 'helpers', 'carbon-fields.php'] )
		);

		/**
		 * Require helper file
		 */
		$filepath = implode( DIRECTORY_SEPARATOR, [$directory, 'app', 'helpers.php'] );
		$statement = 'require_once WPMT_APP_HELPERS_DIR . \'carbon-fields.php\';';

		$this->appendUniqueStatement( $filepath, $statement );

		return '';
	}
}
