<?php

namespace WPEmerge\Cli\Presets;

class Bootstrap extends FrontEndPreset {
	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Bootstrap';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory ) {
		$output = [];

		$output[] = $this->installNodePackage( $directory, 'bootstrap', '^3.3', true );

		$this->addCssVendorImport( $directory, 'bootstrap/dist/css/bootstrap.css' );

		return implode( PHP_EOL, $output );
	}
}
