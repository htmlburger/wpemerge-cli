<?php

namespace WPEmerge\Cli\NodePackageManagers;

use Symfony\Component\Console\Output\OutputInterface;

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
	 * @param  string          $directory
	 * @param  OutputInterface $output
	 * @param  string          $package
	 * @param  string|null     $version
	 * @param  boolean         $dev
	 * @return void
	 */
	public function install( $directory, OutputInterface $output, $package, $version = null, $dev = false );

	/**
	 * Uninstall a package
	 *
	 * @param  string          $directory
	 * @param  OutputInterface $output
	 * @param  string          $package
	 * @param  boolean         $dev
	 * @return void
	 */
	public function uninstall( $directory, OutputInterface $output, $package, $dev = false );
}
