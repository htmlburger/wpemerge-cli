<?php

namespace WPEmerge\Cli\Composer;

use Symfony\Component\Process\Exception\ProcessFailedException;
use WPEmerge\Cli\App;

class Composer {
	/**
	 * Load and parse a composer.json file from a directory
	 *
	 * @param  string     $directory
	 * @return array|null
	 */
	public static function getComposerJson( $directory ) {
		$composer_json = $directory . DIRECTORY_SEPARATOR . 'composer.json';

		if ( ! file_exists( $composer_json ) ) {
			return null;
		}

		$composer = @json_decode( file_get_contents( $composer_json ), true );

		if ( ! $composer ) {
			return null;
		}

		return $composer;
	}

	/**
	 * Store a parsed composer.json file in a directory
	 *
	 * @param  array  $composer
	 * @param  string $directory
	 * @return void
	 */
	public static function storeComposerJson( $composer, $directory ) {
		$composer_json = $directory . DIRECTORY_SEPARATOR . 'composer.json';

		file_put_contents( $composer_json, json_encode( $composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
	}

	/**
	 * Check if a package is already installed
	 *
	 * @param  string  $directory
	 * @param  string  $package
	 * @return boolean
	 */
	public static function installed( $directory, $package ) {
		$command = 'composer show ' . $package . ' -D';

		try {
			App::execute( $command, $directory );
		} catch ( ProcessFailedException $e ) {
			return false;
		}

		return true;
	}

	/**
	 * Install a package
	 *
	 * @param  string      $directory
	 * @param  string      $package
	 * @param  string|null $version
	 * @param  boolean     $dev
	 * @return string
	 */
	public static function install( $directory, $package, $version = null, $dev = false ) {
		$command = 'composer require' .
			( $dev ? ' --dev' : '' ) .
			' "' . $package .( $version !== null ? ':' . $version : '' ) . '"';

		$output = App::execute( $command, $directory );

		return trim( $output );
	}
}
