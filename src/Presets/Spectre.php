<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class Spectre implements PresetInterface {
	use FrontEndPresetTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Spectre.css';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$this->installNodePackage( $directory, $output, 'spectre.css', '^0.5.7' );

		$this->copy([
			$this->path( WPEMERGE_CLI_DIR, 'src', 'Spectre', 'spectre.js' )
			=> $this->path( $directory, 'resources', 'scripts', 'frontend', 'vendor', 'spectre.js' ),
		]);
	}
}
