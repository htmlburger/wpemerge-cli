<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\Composer\Composer;

class CarbonFields implements PresetInterface {
	use FilesystemTrait;

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
		if ( Composer::installed( $directory, $this->package_name ) ) {
			throw new RuntimeException( 'The Carbon Fields composer package is already installed.' );
		}

		Composer::install( $directory, $this->package_name, $this->version_constraint );

		$copy_list = $this->getCopyList( $directory );
		$failures = $this->copy( $copy_list );
		foreach ( $failures as $source => $destination ) {
			$output->writeln( '<failure>File ' . $destination . ' already exists - skipped.</failure>' );
		}

		$this->addRequires( $directory );
		$this->addHooks( $directory );
		$this->addWidgets( $directory );
	}

	/**
	 * Get array of files to copy
	 *
	 * @param  string $directory
	 * @return array
	 */
	protected function getCopyList( $directory ) {
		$copy_list = [
			$this->path( WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'Carbon_Rich_Text_Widget.php' )
				=> $this->path( $directory, 'app', 'src', 'Widgets', 'Carbon_Rich_Text_Widget.php' ),

			$this->path( WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'carbon-fields.php' )
				=> $this->path( $directory, 'app', 'helpers', 'carbon-fields.php' ),
		];

		$source_dir = $this->path( WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'carbon-fields' ) . DIRECTORY_SEPARATOR;
		$destination_dir = $this->path( $directory, 'app', 'setup', 'carbon-fields' ) . DIRECTORY_SEPARATOR;

		$files = scandir( $source_dir );
		$files = array_filter( $files, function( $file ) {
			return preg_match( '~\.php$~', $file );
		} );

		foreach ( $files as $file ) {
			$copy_list[ $source_dir . $file ] = $destination_dir . $file;
		}

		return $copy_list;
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
require_once APP_APP_HELPERS_DIR . 'carbon-fields.php';
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
add_action( 'after_setup_theme', 'app_bootstrap_carbon_fields', 100 );
EOT
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'
add_action( 'carbon_fields_register_fields', 'app_bootstrap_carbon_fields_register_fields' );
EOT
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'
add_filter( 'carbon_fields_map_field_api_key', 'app_filter_carbon_fields_google_maps_api_key' );
EOT
		);
	}

	/**
	 * Add widget statements
	 *
	 * @param  string $directory
	 * @return void
	 */
	protected function addWidgets( $directory ) {
		$widgets_filepath = $this->path( $directory, 'app', 'setup', 'widgets.php' );

		$this->appendUniqueStatement(
			$widgets_filepath,
			<<<'EOT'

/**
 * Rich Text widget
 */
register_widget( App\Widgets\Carbon_Rich_Text_Widget::class );
EOT
		);
	}
}
