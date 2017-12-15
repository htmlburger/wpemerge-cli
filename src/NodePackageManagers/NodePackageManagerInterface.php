<?php

namespace WPEmerge\Cli\NodePackageManagers;

interface NodePackageManagerInterface {
	/**
	 * Check if a package is already installed
	 *
	 * @param  string      $directory
	 * @param  string      $package
	 * @return boolean
	 */
	public function installed( $directory, $package );

	/**
	 * Install a package
	 *
	 * @param  string      $directory
	 * @param  string      $package
	 * @param  string|null $version
	 * @param  boolean     $dev
	 * @return void
	 */
	public function install( $directory, $package, $version = null, $dev = false );

	/**
	 * Uninstall a package
	 *
	 * @param  string  $directory
	 * @param  string  $package
	 * @param  boolean $dev
	 * @return void
	 */
	public function uninstall( $directory, $package, $dev = false );
}
