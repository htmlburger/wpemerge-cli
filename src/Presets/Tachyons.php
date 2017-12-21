<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class Tachyons implements PresetInterface {
	use FrontEndPresetTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Tachyons';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$install_output = $this->installNodePackage( $directory, 'tachyons', '^4.9', true );

		if ( $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE ) {
			$output->writeln( $install_output );
		}

		$this->addCssVendorImport( $directory, 'tachyons/css/tachyons.css' );
	}
}
