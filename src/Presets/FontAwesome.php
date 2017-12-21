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
		$install_output = $this->installNodePackage( $directory, 'font-awesome', '^4.7', true );

		if ( $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE ) {
			$output->writeln( $install_output );
		}

		$this->addCssVendorImport( $directory, 'font-awesome/css/font-awesome.css' );
	}
}
