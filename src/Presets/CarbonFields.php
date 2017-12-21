<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Composer\Composer;

class CarbonFields implements PresetInterface {
	use StatementAppenderTrait;

	/**
	 * Package name
	 *
	 * @var string
	 */
	protected $package_name = 'htmlburger/carbon-fields';

	/**
	 * Version constraint
	 *
	 * @var string|null
	 */
	protected $version_constraint = null;

	/**
	 * Constructor
	 *
	 * @param string $version_constraint
	 */
	public function __construct( $version_constraint ) {
		$this->version_constraint = $version_constraint;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Carbon Fields';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		/**
		 * Make sure Carbon Fields is not already installed
		 */
		if ( Composer::installed( $directory, $this->package_name ) ) {
			throw new RuntimeException( 'The Carbon Fields composer package is already installed.' );
		}

		/**
		 * Require composer package
		 */
		Composer::install( $directory, $this->package_name, $this->version_constraint );

		$source_dir = $this->path( WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'carbon-fields' ) . DIRECTORY_SEPARATOR;
		$destination_dir = $this->path( $directory, 'app', 'setup', 'carbon-fields' ) . DIRECTORY_SEPARATOR;
		$copy_list = [];

		if ( ! is_dir( $destination_dir ) ) {
			mkdir( $destination_dir );
		}

		/**
		 * Setup files
		 */
		$files = scandir( $source_dir );
		$files = array_filter( $files, function( $file ) {
			return preg_match( '~\.php$~', $file );
		} );

		foreach ( $files as $file ) {
			$copy_list[ $source_dir . $file ] = $destination_dir . $file;
		}

		/**
		 * Widget
		 */
		$copy_list[
			$this->path( WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'Carbon_Rich_Text_Widget.php' )
		] = $this->path( $directory, 'app', 'src', 'Widgets', 'Carbon_Rich_Text_Widget.php' );

		/**
		 * Helper file
		 */
		$copy_list[
			$this->path( WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'carbon-fields.php' )
		] = $this->path( $directory, 'app', 'helpers', 'carbon-fields.php' );

		/**
		 * Copy files
		 */
		$failures = $this->copy( $copy_list );
		foreach ( $failures as $source => $destination ) {
			$output->writeln( '<failure>File ' . $destination . ' already exists - skipped.</failure>' );
		}

		/**
		 * Add statements
		 */
		$this->addRequires( $directory );
		$this->addHooks( $directory );
	}

	/**
	 * Add require statements
	 *
	 * @param  string $directory
	 * @return void
	 */
	protected function addRequires( $directory ) {
		$this->appendUniqueStatement(
			$this->path( $directory, 'app', 'helpers.php' ),
			<<<'EOT'
require_once WPMT_APP_HELPERS_DIR . 'carbon-fields.php';
EOT
		);
	}

	/**
	 * Add hook statements
	 *
	 * @param  string $directory
	 * @return void
	 */
	protected function addHooks( $directory ) {
		$hooks_filepath = $this->path( $directory, 'app', 'hooks.php' );

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'

/**
 * Carbon Fields
 */
EOT
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'
add_action( 'after_setup_theme', 'wpmt_boot_carbon_fields', 100 );
EOT
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'
add_action( 'carbon_fields_register_fields', 'wpmt_boot_carbon_fields_register_fields' );
EOT
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'
add_filter( 'carbon_fields_map_field_api_key', 'wpmt_filter_carbon_fields_google_maps_api_key' );
EOT
		);
	}

	/**
	 * Copy a list of files, returning an array of failures
	 *
	 * @param  array $files
	 * @return array
	 */
	protected function copy( $files ) {
		$failures = [];

		foreach ( $files as $source => $destination ) {
			if ( file_exists( $destination ) ) {
				$failures[ $source ] = $destination;
				continue;
			}

			copy( $source, $destination );
		}

		return $failures;
	}

	/**
	 * Join path pieces with appropriate directory separator
	 *
	 * @param  string $path,...
	 * @return string
	 */
	protected function path() {
		return implode( DIRECTORY_SEPARATOR, func_get_args() );
	}
}
