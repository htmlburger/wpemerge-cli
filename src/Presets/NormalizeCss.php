<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class NormalizeCss implements PresetInterface {
	use FrontEndPresetTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Normalize.css';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$this->installNodePackage( $directory, $output, 'normalize.css', '^8.0' );

		$this->copy([
			$this->path( WPEMERGE_CLI_DIR, 'src', 'NormalizeCss', 'normalizecss.js' )
			=> $this->path( $directory, 'resources', 'scripts', 'frontend', 'vendor', 'normalizecss.js' ),
		]);
	}
}
