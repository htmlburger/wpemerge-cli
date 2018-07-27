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
		$this->installNodePackage( $directory, $output, 'bootstrap', '^4.0', true );
		$this->addCssVendorImport( $directory, 'bootstrap/dist/css/bootstrap.css' );
	}
}
