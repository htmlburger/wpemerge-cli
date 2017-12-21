<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\NodePackageManagers\Proxy;

trait FrontEndPresetTrait {
	use StatementAppenderTrait;

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
			throw new RuntimeException( 'Package is already installed.' );
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
		$filepath = implode( DIRECTORY_SEPARATOR, [$directory, 'resources', 'css', '_vendor.css'] );
		$statement = '@import \'~' . $import . '\';';

		$this->appendUniqueStatement( $filepath, $statement );
	}
}
