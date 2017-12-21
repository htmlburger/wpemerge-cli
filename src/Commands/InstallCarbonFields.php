<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Presets\CarbonFields;

class InstallCarbonFields extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install:carbon-fields' )
			->setDescription( 'Install Carbon Fields.' )
			->setHelp( 'Install Carbon Fields as a composer package and add relevant helpers files.' )
			->addArgument(
				'version',
				InputArgument::OPTIONAL,
				'Version constraint.'
			);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$version = $input->getArgument( 'version' );

		$preset = new CarbonFields( $version );
		$preset->execute( getcwd(), $output );
	}
}
