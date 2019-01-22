<?php

namespace WPEmerge\Cli\NodePackageManagers;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\App;

class Yarn implements NodePackageManagerInterface {
	/**
	 * {@inheritDoc}
	 */
	public function installed( $directory, $package ) {
		$command = 'yarn list ' . escapeshellarg( $package ) . ' --depth=0 --json';

		$output = App::execute( $command, $directory );
		$json = @json_decode( trim( $output ), true );

		if ( $json === null ) {
			throw new RuntimeException( 'Could not determine if the ' . $package . ' package is already installed.' );
		}

		if ( count( $json['data']['trees'] ) === 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function install( $directory, OutputInterface $output, $package, $version = null, $dev = false ) {
		$command = 'yarn add ' .
			'"' . $package . ( $version !== null ? '@' . $version : '' ) . '"' .
			( $dev ? ' --dev' : '' );

		App::liveExecute( $command, $output, $directory, 600 );
	}

	/**
	 * {@inheritDoc}
	 */
	public function uninstall( $directory, OutputInterface $output, $package, $dev = false ) {
		$command = 'yarn remove ' .
			escapeshellarg( $package ) .
			( $dev ? ' --dev' : '' );

		App::liveExecute( $command, $output, $directory, 600 );
	}

	/**
	 * {@inheritDoc}
	 */
	public function installAll( $directory, OutputInterface $output ) {
		$command = 'yarn install';

		App::liveExecute( $command, $output, $directory, 600 );
	}

	/**
	 * {@inheritDoc}
	 */
	public function run( $directory, OutputInterface $output, $script ) {
		$command = 'yarn run ' . $script;

		App::liveExecute( $command, $output, $directory, 600 );
	}
}
