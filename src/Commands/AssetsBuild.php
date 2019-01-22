<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\NodePackageManagers\Proxy;
use WPEmerge\Cli\Presets\FilesystemTrait;

class AssetsBuild extends Command {
	use FilesystemTrait;

	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'assets:build' )
			->setDescription( 'Build theme assets.' )
			->setHelp( 'Builds all theme assets.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$package_manager = new Proxy();

		$package_manager->run( getcwd(), $output, 'build' );
	}
}
