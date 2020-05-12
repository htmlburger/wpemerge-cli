<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class Bulma implements PresetInterface {
	use FrontEndPresetTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Bulma';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$this->installNodePackage( $directory, $output, 'bulma', '^0.6' );

		$this->copy([
			$this->path( WPEMERGE_CLI_DIR, 'src', 'Bulma', 'bulma.js' )
			=> $this->path( $directory, 'resources', 'scripts', 'frontend', 'vendor', 'bulma.js' ),
		]);
	}
}
