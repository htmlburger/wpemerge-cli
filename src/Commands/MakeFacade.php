<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Templates\Facade;

class MakeFacade extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'make:facade' )
			->setDescription( 'Creates a facade class file.' )
			->setHelp( 'Creates a facade class file.' )
			->addArgument( 'name', InputArgument::REQUIRED, 'Desired class name in CamelCase.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$template = new Facade();
		$filepath = $template->create( $input->getArgument( 'name' ), getcwd() );

		$output->writeln( 'Facade created successfully:' );
		$output->writeln( '<info>' . $filepath . '</info>' );
	}
}
