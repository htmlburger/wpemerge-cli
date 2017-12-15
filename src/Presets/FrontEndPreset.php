<?php

namespace WPEmerge\Cli\Presets;

use Exception;
use WPEmerge\Cli\NodePackageManagers\Proxy;

abstract class FrontEndPreset implements PresetInterface {
	/**
	 * Install a node package
	 *
	 * @param  string      $directory
	 * @param  string      $package
	 * @param  string|null $version
	 * @param  boolean     $version
	 * @return string
	 */
	protected function installNodePackage( $directory, $package, $version = null, $dev = false ) {
		$package_manager = new Proxy();

		if ( $package_manager->installed( $directory, $package ) ) {
			throw new Exception( 'Package is already installed.' );
		}

		return $package_manager->install( $directory, $package, $version, $dev );
	}

	/**
	 * Install a node package
	 *
	 * @param  string $import
	 * @return void
	 */
	protected function addCssVendorImport( $directory, $import ) {
		$css_path = $directory . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . '_vendor.css';
		$css = file_get_contents( $css_path );

		$import = '@import \'~' . $import . '\';';
		$regex = '~^' . preg_quote( $import, '~' ) . '~m';

		if ( preg_match( $regex, $css ) ) {
			return; // import statement is already exists
		}

		$css .= PHP_EOL . $import . PHP_EOL;
		file_put_contents( $css_path, $css );
	}
}
