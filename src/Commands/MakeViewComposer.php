<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Templates\ViewComposer;

class MakeViewComposer extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'make:view-composer' )
			->setDescription( 'Creates a view composer class file.' )
			->setHelp( 'Creates a view composer class file.' )
			->addArgument( 'name', InputArgument::REQUIRED, 'Desired class name in CamelCase.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$template = new ViewComposer();
		$filepath = $template->create( $input->getArgument( 'name' ), getcwd() );

		$output->writeln( 'View composer created successfully:' );
		$output->writeln( '<info>' . $filepath . '</info>' );
	}
}
