<?php

namespace WPEmerge\Cli\Presets;

interface PresetInterface {
	/**
	 * Get preset name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Execute the preset
	 *
	 * @param  string $directory
	 * @return string
	 */
	public function execute( $directory );
}
