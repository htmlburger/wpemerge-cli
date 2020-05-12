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
		$this->installNodePackage( $directory, $output, 'tailwindcss', '^1.1.2' );

		$tailwind_scss_filepath = $this->path( WPEMERGE_CLI_DIR, 'src', 'TailwindCss', 'tailwind.scss' );
		$index_scss_filepath = $this->path( $directory, 'resources', 'styles', 'frontend', 'index.scss' );
		$this->appendUniqueStatement( $index_scss_filepath, file_get_contents( $tailwind_scss_filepath ) );

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
			App::execute( escapeshellarg( $tailwind_bin_filepath ) . ' init ' . escapeshellarg( $tailwind_js_filepath ), $directory );
		}
	}
}
