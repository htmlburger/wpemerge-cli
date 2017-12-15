<?php

namespace WPEmerge\Cli\NodePackageManagers;

use WPEmerge\Cli\App;

class Yarn implements NodePackageManagerInterface {
	/**
	 * {@inheritDoc}
	 */
	public function installed( $directory, $package ) {
		$command = 'yarn list ' . $package . ' --depth=0 --json';

		$output = App::execute( $command, $directory );
		$json = @json_decode( trim( $output ), true );

		if ( ! $json ) {
			throw new Exception( 'Could not determine if the ' . $package . ' package is already installed.' );
		}

		if ( count( $json['data']['trees'] ) === 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	public function install( $directory, $package, $version = null, $dev = false ) {
		$command = 'yarn add ' .
			'"' . $package .( $version !== null ? '@' . $version : '' ) . '"' .
			( $dev ? ' --dev' : '' );

		$output = App::execute( $command, $directory );

		return trim( $output );
	}

	/**
	 * {@inheritDoc}
	 */
	public function uninstall( $directory, $package, $dev = false ) {
		$command = 'yarn remove ' .
			$package .
			( $dev ? ' --dev' : '' );

		$output = App::execute( $command, $directory );

		return trim( $output );
	}
}
