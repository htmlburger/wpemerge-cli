<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\NodePackageManagers\Proxy;

trait BackEndPresetTrait {
	use StatementAppenderTrait;

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
			if ( ! is_dir( $directory ) ) {
				mkdir( $directory );
			}

			copy( $source, $destination );
		}

		return $failures;
	}

	/**
	 * Join path pieces with appropriate directory separator
	 *
	 * @param  string $path,...
	 * @return string
	 */
	protected function path() {
		return implode( DIRECTORY_SEPARATOR, func_get_args() );
	}
}
