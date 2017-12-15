<?php

namespace WPEmerge\Cli\NodePackageManagers;

use Exception;
use Symfony\Component\Process\Exception\ProcessFailedException;
use WPEmerge\Cli\App;

class Proxy implements NodePackageManagerInterface {
	/**
	 * {@inheritDoc}
	 */
	public function installed( $directory, $package ) {
		return call_user_func_array( [$this->getNodePackageManager(), 'installed'], func_get_args() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function install( $directory, $package, $version = null, $dev = false ) {
		return call_user_func_array( [$this->getNodePackageManager(), 'install'], func_get_args() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function uninstall( $directory, $package, $dev = false ) {
		return call_user_func_array( [$this->getNodePackageManager(), 'uninstall'], func_get_args() );
	}

	protected function getNodePackageManager() {
		$node_package_managers = [
			'yarn' => Yarn::class,
			'npm' => Npm::class,
		];

		foreach ( $node_package_managers as $manager => $class ) {
			$command = 'which ' . $manager;

			try {
				$output = App::execute( $command );
			} catch ( ProcessFailedException $e ) {
				continue;
			}

			if ( ! trim( $output ) ) {
				continue;
			}

			return new $class();
		}

		throw new Exception( 'Could not find a node package manager. Please check if npm is added to your PATH.' );
	}
}
