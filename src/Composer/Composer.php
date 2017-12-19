<?php

namespace WPEmerge\Cli\Composer;

class Composer {
	/**
	 * Load and parse a composer.json file from a directory
	 *
	 * @param  string      $directory
	 * @return object|null
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
}
