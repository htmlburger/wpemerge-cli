<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\App;

class TailwindCss implements PresetInterface {
	use FrontEndPresetTrait;
	use PresetEnablerTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Tailwind CSS';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$this->installNodePackage( $directory, $output, 'tailwindcss', '^2' );

		$this->copy([
			$this->path( WPEMERGE_CLI_DIR, 'src', 'TailwindCss', 'tailwindcss.scss' )
			=> $this->path( $directory, 'resources', 'styles', 'frontend', 'vendor', 'tailwindcss.scss' ),
		]);

		$postcss_js_filepath = $this->path( $directory, 'resources', 'build', 'postcss.js' );
		$this->enablePreset( $postcss_js_filepath, 'Tailwind CSS' );

		$components_dir = $this->path( $directory, 'resources', 'styles', 'frontend', 'components' );
		if ( ! file_exists( $components_dir ) ) {
			mkdir( $components_dir );
		}

		$utilities_dir = $this->path( $directory, 'resources', 'styles', 'frontend', 'utilities' );
		if ( ! file_exists( $utilities_dir ) ) {
			mkdir( $utilities_dir );
		}

		$tailwind_bin_filepath = $this->path( $directory, 'node_modules', '.bin', 'tailwind' );
		$tailwind_js_filepath = $this->path( $directory, 'resources', 'build', 'tailwindcss.js' );
		if ( ! file_exists( $tailwind_js_filepath ) ) {
			App::execute( [$tailwind_bin_filepath, 'init', $tailwind_js_filepath, $directory] );
		}
	}
}
