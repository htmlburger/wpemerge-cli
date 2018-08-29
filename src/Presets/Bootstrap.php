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
		$this->addJsVendorImport( $directory, 'popper.js' );
		$this->addJsVendorImport( $directory, 'bootstrap' );
		$this->addCssVendorImport( $directory, 'bootstrap/dist/css/bootstrap.css' );
	}
}
