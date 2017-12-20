<?php

namespace WPEmerge\Cli\Presets;

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
	public function execute( $directory ) {
		$output = [];

		$output[] = $this->installNodePackage( $directory, 'foundation-sites', '^6.4', true );

		$this->addCssVendorImport( $directory, 'foundation-sites/dist/css/foundation.css' );

		return implode( PHP_EOL, $output );
	}
}
