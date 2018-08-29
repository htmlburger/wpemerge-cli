<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\Presets\FilesystemTrait;

class CreateConfigJson extends Command {
	use FilesystemTrait;

	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'config:create' )
			->setDescription( 'Create a config.json file.' )
			->setHelp( 'Creates a config.json file by cloning the config.json.dist file.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$source = $this->path( getcwd(), 'config.json.dist' );
		$target = $this->path( getcwd(), 'config.json' );

		if ( ! file_exists( $source ) ) {
			throw new RuntimeException( 'Could not find the required ' . basename( $source ) . ' file.' );
		}

		if ( file_exists( $target ) ) {
			throw new RuntimeException( 'A ' . basename( $target ) . ' file already exists.' );
		}

		copy( $source, $target );
	}
}
