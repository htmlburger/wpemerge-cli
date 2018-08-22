<?php

namespace WPEmerge\Cli\Presets;

trait FilesystemTrait {
	/**
	 * Join path pieces with appropriate directory separator
	 *
	 * @param  string $path,...
	 * @return string
	 */
	protected function path() {
		return implode( DIRECTORY_SEPARATOR, func_get_args() );
	}

	/**
	 * Copy a list of files, returning an array of failures
	 *
	 * @param  array $files
	 * @return array
	 */
	protected function copy( $files ) {
		$failures = [];

		foreach ( $files as $source => $destination ) {
			if ( file_exists( $destination ) ) {
				$failures[ $source ] = $destination;
				continue;
			}

			$directory = dirname( $destination );
			if ( ! file_exists( $directory ) ) {
				mkdir( $directory );
			}

			copy( $source, $destination );
		}

		return $failures;
	}

	/**
	 * Append a statement to file
	 *
	 * @param  string $filepath
	 * @param  string $statement
	 * @return void
	 */
	protected function appendUniqueStatement( $filepath, $statement ) {
		$contents = file_get_contents( $filepath );
		$regex = '~^\s*(' . preg_quote( $statement, '~' ) . ')\s*$~m';

		if ( preg_match( $regex, $contents ) ) {
			return; // statement already exists
		}

		$content_lines = explode( "\n", $contents );
		$last_line = trim( $content_lines[ count( $content_lines ) - 1 ] );

		if ( empty( $last_line ) ) {
			$contents .= $statement . PHP_EOL;
		} else {
			$contents .= PHP_EOL . $statement;
		}

		file_put_contents( $filepath, $contents );
	}
}
