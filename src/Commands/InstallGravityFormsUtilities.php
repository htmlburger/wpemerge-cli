<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Presets\GravityFormsUtilities;

class InstallGravityFormsUtilities extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install:gravity-forms-utilities' )
			->setDescription( 'Install custom Gravity Forms utilities.' )
			->setHelp( 'Install custom Gravity Forms utilities.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$preset = new GravityFormsUtilities();
		$preset->execute( getcwd(), $output );
	}
}
