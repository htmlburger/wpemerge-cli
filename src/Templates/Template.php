<?php

namespace WPEmerge\Cli\Templates;

use Symfony\Component\Console\Exception\InvalidArgumentException;

abstract class Template {
	/**
	 * Create a new class file
	 *
	 * @param  string $name
	 * @param  string $directory
	 * @return string
	 */
	abstract public function create( $name, $directory );

	/**
	 * Store file on disc returning the filepath
	 *
	 * @param  string $name
	 * @param  string $namespace
	 * @param  string $contents
	 * @param  string $directory
	 * @return string
	 */
	public function storeOnDisc( $name, $namespace, $contents, $directory ) {
		$filepath = implode( DIRECTORY_SEPARATOR, [$directory, 'app', 'src', $namespace, $name . '.php'] );

		if ( file_exists( $filepath ) ) {
			throw new InvalidArgumentException( 'Class file already exists (' . $filepath . ')' );
		}

		file_put_contents( $filepath, $contents );

		return $filepath;
	}
}
