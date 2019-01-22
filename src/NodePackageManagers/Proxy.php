<?php

namespace WPEmerge\Cli\NodePackageManagers;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\RuntimeException;
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
	public function install( $directory, OutputInterface $output, $package, $version = null, $dev = false ) {
		call_user_func_array( [$this->getNodePackageManager(), 'install'], func_get_args() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function uninstall( $directory, OutputInterface $output, $package, $dev = false ) {
		call_user_func_array( [$this->getNodePackageManager(), 'uninstall'], func_get_args() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function installAll( $directory, OutputInterface $output ) {
		call_user_func_array( [$this->getNodePackageManager(), 'installAll'], func_get_args() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function run( $directory, OutputInterface $output, $script ) {
		call_user_func_array( [$this->getNodePackageManager(), 'run'], func_get_args() );
	}

	/**
	 * Get an instance of the first available package manager
	 *
	 * @return NodePackageManagerInterface
	 */
	protected function getNodePackageManager() {
		$is_windows = strtolower( substr( PHP_OS, 0, 3 ) ) === 'win';
		$node_package_managers = [
			'yarn' => Yarn::class,
			'npm' => Npm::class,
		];

		foreach ( $node_package_managers as $manager => $class ) {
			$command = $is_windows ? 'where ' . escapeshellarg( $manager ) : 'which ' . escapeshellarg( $manager );

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

		throw new RuntimeException( 'Could not find a node package manager. Please check if npm is added to your PATH.' );
	}
}
