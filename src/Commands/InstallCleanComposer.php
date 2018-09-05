<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Composer\Composer;

class InstallCleanComposer extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install:clean-composer' )
			->setDescription( 'Clean composer.json from author information.' )
			->setHelp( 'Clean composer.json from author information.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$directory = getcwd();

		$composer = Composer::getComposerJson( $directory );

		if ( $composer === null ) {
			$output->writeln( '<failure>Could not find composer.json - skipped cleaning.</failure>' );
			return;
		}

		unset( $composer['name'] );
		unset( $composer['description'] );
		unset( $composer['homepage'] );
		unset( $composer['authors'] );

		Composer::storeComposerJson( $composer, $directory );
	}
}
