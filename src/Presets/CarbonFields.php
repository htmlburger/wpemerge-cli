<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\Composer\Composer;

class CarbonFields implements PresetInterface {
	use FilesystemTrait;

	/**
	 * Package name.
	 *
	 * @var string
	 */
	protected $package_name = 'htmlburger/carbon-fields';

	/**
	 * Version constraint.
	 *
	 * @var string|null
	 */
	protected $version_constraint = null;

	/**
	 * Constructor.
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

		$output->writeln( '<comment>Please add \\MyApp\\CarbonFields\\CarbonFieldsServiceProvider::class to your providers array.</comment>' );
	}

	/**
	 * Get array of files to copy.
	 *
	 * @param  string $directory
	 * @return array
	 */
	protected function getCopyList( $directory ) {
		$copy_list = [
			$this->path( WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'Carbon_Rich_Text_Widget.php' )
				=> $this->path( $directory, 'app', 'src', 'Widgets', 'Carbon_Rich_Text_Widget.php' ),

			$this->path( WPEMERGE_CLI_DIR, 'src', 'CarbonFields', 'CarbonFieldsServiceProvider.php' )
				=> $this->path( $directory, 'app', 'src', 'CarbonFields', 'CarbonFieldsServiceProvider.php' ),
		];

		return $copy_list;
	}
}
