<?php

namespace WPEmerge\Cli\Presets;

trait PresetEnablerTrait {
	/**
	 * Uncomment preset statements in file.
	 *
	 * @param  string $filepath
	 * @param  string $preset
	 * @return void
	 */
	protected function enablePreset( $filepath, $preset ) {
		$begin = sprintf( '~^\s*/\* @preset-begin\(%1$s\).*?\r?\n~mi', preg_quote( $preset, '~' ) );
		$end = sprintf( '~^\s*@preset-end\(%1$s\) \*/\s*?\r?\n~mi', preg_quote( $preset, '~' ) );
		$contents = file_get_contents( $filepath );

		if ( ! preg_match( $begin, $contents ) || ! preg_match( $end, $contents ) ) {
			return;
		}

		$contents = preg_replace( $begin, '', $contents );
		$contents = preg_replace( $end, '', $contents );

		file_put_contents( $filepath, $contents );
	}
}
