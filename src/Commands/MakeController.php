<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Templates\Controller;

class MakeController extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'make:controller' )
			->setDescription( 'Creates a controller class file.' )
			->setHelp( 'Creates a controller class file.' )
			->addArgument( 'name', InputArgument::REQUIRED, 'Desired class name in CamelCase.' )
			->addOption( 'type', 't', InputOption::VALUE_REQUIRED, 'Desired controller type: web, admin, ajax.', 'web' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$types = [
			'web' => 'Web',
			'admin' => 'Admin',
			'ajax' => 'Ajax',
		];

		$type = $input->getOption( 'type' );

		if ( ! isset( $types[ $type ] ) ) {
			throw new RuntimeException( 'Unknown controller type: ' . $type . "\n" . 'Should be one of: web, admin, ajax.' );
		}

		$template = new Controller();
		$filepath = $template->create( $types[ $type ] . '\\' . $input->getArgument( 'name' ), getcwd() );

		$output->writeln( 'Controller created successfully:' );
		$output->writeln( '<info>' . $filepath . '</info>' );
	}
}
