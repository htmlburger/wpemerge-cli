<?php

namespace WPEmerge\Cli\Presets;

class Tachyons extends FrontEndPreset {
	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Tachyons';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory ) {
		$output = [];

		$output[] = $this->installNodePackage( $directory, 'tachyons', '^4.9', true );

		$this->addCssVendorImport( $directory, 'tachyons/css/tachyons.css' );

		return implode( PHP_EOL, $output );
	}
}
