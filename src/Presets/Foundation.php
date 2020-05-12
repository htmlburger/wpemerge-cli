<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class Foundation implements PresetInterface {
	use FrontEndPresetTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Foundation';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$this->installNodePackage( $directory, $output, 'foundation-sites', '^6.4' );

		$this->copy([
			$this->path( WPEMERGE_CLI_DIR, 'src', 'Foundation', 'foundation.js' )
			=> $this->path( $directory, 'resources', 'scripts', 'frontend', 'vendor', 'foundation.js' ),
		]);
	}
}
