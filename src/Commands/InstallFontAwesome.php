<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Presets\FontAwesome;

class InstallFontAwesome extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install:font-awesome' )
			->setDescription( 'Install Font Awesome.' )
			->setHelp( 'Install Font Awesome.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$preset = new FontAwesome();
		$preset->execute( getcwd(), $output );
	}
}
