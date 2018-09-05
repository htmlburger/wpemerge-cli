<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class PhpTests implements PresetInterface {
	use FilesystemTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'PHP Tests';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$directories = [
			$this->path( $directory, 'tests' ),
			$this->path( $directory, 'tests', 'php' ),
			$this->path( $directory, 'tests', 'php', 'bin' ),
			$this->path( $directory, 'tests', 'php', 'misc' ),
			$this->path( $directory, 'tests', 'php', 'unit-tests' ),
		];

		foreach ( $directories as $dir ) {
			if ( ! file_exists( $dir ) ) {
				mkdir( $dir );
			}
		}

		$copy_list = [
			$this->path( WPEMERGE_CLI_DIR, 'src', 'PhpTests', 'tests', 'php', 'bin', 'install.sh' )
				=> $this->path( $directory, 'tests', 'php', 'bin', 'install.sh' ),

			$this->path( WPEMERGE_CLI_DIR, 'src', 'PhpTests', 'tests', 'php', 'misc', 'db.php' )
				=> $this->path( $directory, 'tests', 'php', 'misc', 'db.php' ),

			$this->path( WPEMERGE_CLI_DIR, 'src', 'PhpTests', 'tests', 'php', 'unit-tests', 'ExampleTest.php' )
				=> $this->path( $directory, 'tests', 'php', 'unit-tests', 'ExampleTest.php' ),

			$this->path( WPEMERGE_CLI_DIR, 'src', 'PhpTests', 'tests', 'php', 'bootstrap.php' )
				=> $this->path( $directory, 'tests', 'php', 'bootstrap.php' ),

			$this->path( WPEMERGE_CLI_DIR, 'src', 'PhpTests', 'tests', 'php', 'README.md' )
				=> $this->path( $directory, 'tests', 'php', 'README.md' ),

			$this->path( WPEMERGE_CLI_DIR, 'src', 'PhpTests', 'phpunit.xml' )
				=> $this->path( $directory, 'phpunit.xml' ),
		];

		$failures = $this->copy( $copy_list );

		foreach ( $failures as $source => $destination ) {
			$output->writeln( '<failure>File ' . $destination . ' already exists - skipped.</failure>' );
		}
	}
}
