<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Presets\PhpTests;

class InstallPhpTests extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install:php-tests' )
			->setDescription( 'Install php unit testing environment.' )
			->setHelp( 'Install php unit testing environment.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$preset = new PhpTests();
		$preset->execute( getcwd(), $output );
	}
}
