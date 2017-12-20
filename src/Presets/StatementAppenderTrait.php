<?php

namespace WPEmerge\Cli\Presets;

use Exception;
use WPEmerge\Cli\NodePackageManagers\Proxy;

trait StatementAppenderTrait {
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
