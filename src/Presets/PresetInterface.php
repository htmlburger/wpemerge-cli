<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

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
	 * @param  string          $directory
	 * @param  OutputInterface $output
	 * @return void
	 */
	public function execute( $directory, OutputInterface $output );
}
