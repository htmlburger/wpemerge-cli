<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

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
		$this->installNodePackage( $directory, $output, 'tailwindcss', '^3' );

		$this->copy([
			$this->path( WPEMERGE_CLI_DIR, 'src', 'TailwindCss', 'tailwindcss.scss' )
			=> $this->path( $directory, 'resources', 'styles', 'frontend', 'vendor', 'tailwindcss.scss' ),
			$this->path( WPEMERGE_CLI_DIR, 'src', 'TailwindCss', 'tailwindcss.js' )
			=> $this->path( $directory, 'resources', 'build', 'tailwindcss.js' ),
		]);

		$postcss_js_filepath = $this->path( $directory, 'resources', 'build', 'postcss.js' );
		$this->enablePreset( $postcss_js_filepath, 'Tailwind CSS' );

		$development_mode_plugin_js_filepath = $this->path( $directory, 'resources', 'build', 'lib', 'development-mode-plugin.js' );
		$this->enablePreset( $development_mode_plugin_js_filepath, 'Tailwind CSS' );

		$components_dir = $this->path( $directory, 'resources', 'styles', 'frontend', 'components' );
		if ( ! file_exists( $components_dir ) ) {
			mkdir( $components_dir );
		}

		$utilities_dir = $this->path( $directory, 'resources', 'styles', 'frontend', 'utilities' );
		if ( ! file_exists( $utilities_dir ) ) {
			mkdir( $utilities_dir );
		}
	}
}
