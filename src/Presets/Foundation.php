<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

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
	public function execute( $directory, OutputInterface $output ) {
		$install_output = $this->installNodePackage( $directory, 'foundation-sites', '^6.4', true );

		if ( $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE ) {
			$output->writeln( $install_output );
		}

		$this->addCssVendorImport( $directory, 'foundation-sites/dist/css/foundation.css' );
	}
}
