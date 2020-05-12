<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\NodePackageManagers\Proxy;
use WPEmerge\Cli\Presets\FilesystemTrait;

class InstallDependencies extends Command {
	use FilesystemTrait;

	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install:dependencies' )
			->setDescription( 'Install Node dependencies.' )
			->setHelp( 'Installs all required Node dependencies.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$package_manager = new Proxy();

		$package_manager->installAll( getcwd(), $output );
	}
}
