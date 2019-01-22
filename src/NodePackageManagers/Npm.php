<?php

namespace WPEmerge\Cli\NodePackageManagers;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\App;

class Npm implements NodePackageManagerInterface {
	/**
	 * {@inheritDoc}
	 */
	public function installed( $directory, $package ) {
		$command = 'npm list ' . escapeshellarg( $package ) . ' --json';

		try {
			$output = App::execute( $command, $directory );
		} catch ( ProcessFailedException $exception ) {
			$output = $exception->getProcess()->getOutput();
		}

		$json = @json_decode( trim( $output ), true );

		if ( $json === null ) {
			throw new RuntimeException( 'Could not determine if the ' . $package . ' package is already installed.' );
		}

		if ( empty( $json['dependencies'] ) && empty( $json['devDependencies'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function install( $directory, OutputInterface $output, $package, $version = null, $dev = false ) {
		$command = 'npm install ' .
			'"' . $package . ( $version !== null ? '@' . $version : '' ) . '"' .
			( $dev ? ' --only=dev' : '' );

		App::liveExecute( $command, $output, $directory, 600 );
	}

	/**
	 * {@inheritDoc}
	 */
	public function uninstall( $directory, OutputInterface $output, $package, $dev = false ) {
		$command = 'npm uninstall ' .
			escapeshellarg( $package ) .
			( $dev ? ' --only=dev' : '' );

		App::liveExecute( $command, $output, $directory, 600 );
	}

	/**
	 * {@inheritDoc}
	 */
	public function installAll( $directory, OutputInterface $output ) {
		$command = 'npm install';

		App::liveExecute( $command, $output, $directory, 600 );
	}

	/**
	 * {@inheritDoc}
	 */
	public function run( $directory, OutputInterface $output, $script ) {
		$command = 'npm run ' . $script;

		App::liveExecute( $command, $output, $directory, 600 );
	}
}
