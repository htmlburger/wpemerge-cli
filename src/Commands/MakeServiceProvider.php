<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Templates\ServiceProvider;

class MakeServiceProvider extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'make:service-provider' )
			->setDescription( 'Creates a service provider class file.' )
			->setHelp( 'Creates a service provider class file.' )
			->addArgument( 'name', InputArgument::REQUIRED, 'Desired class name in CamelCase.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$template = new ServiceProvider();
		$filepath = $template->create( $input->getArgument( 'name' ), getcwd() );

		$output->writeln( 'Service provider created successfully:' );
		$output->writeln( '<info>' . $filepath . '</info>' );
	}
}
