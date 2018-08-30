<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class FontAwesome implements PresetInterface {
	use FrontEndPresetTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Font Awesome';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$this->installNodePackage( $directory, $output, 'font-awesome', '^4.7' );

		$this->copy([
			$this->path( WPEMERGE_CLI_DIR, 'src', 'FontAwesome', 'fontawesome.js' )
			=> $this->path( $directory, 'resources', 'scripts', 'theme', 'vendor', 'fontawesome.js' ),
		]);
	}
}
