<?php

namespace WPEmerge\Cli\Presets;

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
	public function execute( $directory ) {
		$output = [];

		$output[] = $this->installNodePackage( $directory, 'bulma', '^0.6', true );

		$this->addCssVendorImport( $directory, 'bulma/css/bulma.css' );

		return implode( PHP_EOL, $output );
	}
}
