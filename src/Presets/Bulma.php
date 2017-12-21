<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

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
	public function execute( $directory, OutputInterface $output ) {
		$install_output = $this->installNodePackage( $directory, 'bulma', '^0.6', true );

		if ( $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE ) {
			$output->writeln( $install_output );
		}

		$this->addCssVendorImport( $directory, 'bulma/css/bulma.css' );
	}
}
