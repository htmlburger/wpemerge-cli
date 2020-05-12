<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class Bootstrap implements PresetInterface {
	use FrontEndPresetTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Bootstrap';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$this->installNodePackage( $directory, $output, 'popper.js', '^1.14' );
		$this->installNodePackage( $directory, $output, 'bootstrap', '^4.0' );

		$this->copy([
			$this->path( WPEMERGE_CLI_DIR, 'src', 'Bootstrap', 'bootstrap.js' )
				=> $this->path( $directory, 'resources', 'scripts', 'frontend', 'vendor', 'bootstrap.js' ),
		]);
	}
}
