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
		$namespace_pieces = explode( '\\', $namespace );
		$filepath = implode( DIRECTORY_SEPARATOR, array_merge(
			[$directory, 'app', 'src'],
			$namespace_pieces,
			[$name . '.php']
		) );

		if ( file_exists( $filepath ) ) {
			throw new InvalidArgumentException( 'Class file already exists (' . $filepath . ')' );
		}

		if ( ! file_exists( dirname( $filepath ) ) ) {
			mkdir( dirname( $filepath ), 0777, true );
		}

		file_put_contents( $filepath, $contents );

		return $filepath;
	}
}
