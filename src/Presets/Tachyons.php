<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class Tachyons implements PresetInterface {
	use FrontEndPresetTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Tachyons';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$this->installNodePackage( $directory, $output, 'tachyons', '^4.9' );

		$this->copy([
			$this->path( WPEMERGE_CLI_DIR, 'src', 'Tachyons', 'tachyons.js' )
			=> $this->path( $directory, 'resources', 'scripts', 'frontend', 'vendor', 'tachyons.js' ),
		]);
	}
}
