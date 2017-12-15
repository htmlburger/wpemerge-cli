<?php

namespace WPEmerge\Cli\NodePackageManagers;

use Symfony\Component\Process\Exception\ProcessFailedException;
use WPEmerge\Cli\App;

class Npm implements NodePackageManagerInterface {
	/**
	 * {@inheritDoc}
	 */
	public function installed( $directory, $package ) {
		$command = 'npm list ' . $package . ' --json';

		$output = App::execute( $command, $directory );
		$json = @json_decode( trim( $output ), true );

		if ( ! $json ) {
			throw new Exception( 'Could not determine if the ' . $package . ' package is already installed.' );
		}

		if ( empty( $json['dependencies'] ) && empty( $json['devDependencies'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function install( $directory, $package, $version = null, $dev = false ) {
		$command = 'npm install ' .
			'"' . $package .( $version !== null ? '@' . $version : '' ) . '"' .
			( $dev ? ' --only=dev' : '' );

		$output = App::execute( $command, $directory );

		return trim( $output );
	}

	/**
	 * {@inheritDoc}
	 */
	public function uninstall( $directory, $package, $dev = false ) {
		$command = 'npm uninstall ' .
			$package .
			( $dev ? ' --only=dev' : '' );

		$output = App::execute( $command, $directory );

		return trim( $output );
	}
}
