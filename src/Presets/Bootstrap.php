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
		$install_output = $this->installNodePackage( $directory, 'bootstrap', '^3.3', true );

		if ( $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE ) {
			$output->writeln( $install_output );
		}

		$this->addCssVendorImport( $directory, 'bootstrap/dist/css/bootstrap.css' );
	}
}
