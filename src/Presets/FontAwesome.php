<?php

namespace WPEmerge\Cli\Presets;

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
	public function execute( $directory ) {
		$output = [];

		$output[] = $this->installNodePackage( $directory, 'font-awesome', '^4.7', true );

		$this->addCssVendorImport( $directory, 'font-awesome/css/font-awesome.css' );

		return implode( PHP_EOL, $output );
	}
}
