<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\NodePackageManagers\Proxy;

trait FrontEndPresetTrait {
	use FilesystemTrait;

	/**
	 * Install a node package.
	 *
	 * @param  string          $directory
	 * @param  OutputInterface $output
	 * @param  string          $package
	 * @param  string|null     $version
	 * @param  boolean         $dev
	 * @return void
	 */
	protected function installNodePackage( $directory, OutputInterface $output, $package, $version = null, $dev = false ) {
		$package_manager = new Proxy();

		if ( $package_manager->installed( $directory, $package ) ) {
			throw new RuntimeException( 'Package is already installed.' );
		}

		$package_manager->install( $directory, $output, $package, $version, $dev );
	}

	/**
	 * Add an @import statement to the _vendor.scss file.
	 *
	 * @param  string $directory
	 * @param  string $import
	 * @return void
	 */
	protected function addCssVendorImport( $directory, $import ) {
		$filepath = $this->path( $directory, 'resources', 'styles', 'frontend', '_vendor.scss' );
		$statement = "@import '~$import';";

		$this->appendUniqueStatement( $filepath, $statement );
	}

	/**
	 * Add an import statement to the vendor.js file.
	 *
	 * @param  string $directory
	 * @param  string $import
	 * @return void
	 */
	protected function addJsVendorImport( $directory, $import ) {
		$filepath = $this->path( $directory, 'resources', 'scripts', 'frontend', 'vendor.js' );
		$statement = "import '$import';";

		$this->appendUniqueStatement( $filepath, $statement );
	}
}
