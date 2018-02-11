<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WPEmerge\Cli\Composer\Composer;

class InstallHtmlBurgerEnv extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install:htmlburger-env' )
			->setDescription( 'Install the default htmlBurger configuration.' )
			->setHelp( 'Install the default htmlBurger configuration.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$directory = getcwd();
		$clean_composer = $this->getApplication()->find( 'install:clean-composer');
		$install_carbon_fields = $this->getApplication()->find( 'install:carbon-fields');
		$install_gravity_forms_utilities = $this->getApplication()->find( 'install:gravity-forms-utilities' );

		$clean_composer->run( new ArrayInput( [
			'command' => $clean_composer->getName(),
		] ), $output );

		$install_carbon_fields->run( new ArrayInput( [
			'command' => $install_carbon_fields->getName(),
		] ), $output );

		$install_gravity_forms_utilities->run( new ArrayInput( [
			'command' => $install_gravity_forms_utilities->getName(),
		] ), $output );

		$composer_packages = [
			'require' => [
				'htmlburger/theme-help' => '^1.1.7',
			],
			'require-dev' => [
				'htmlburger/carbon-debug' => '^1.0.2',
			],
		];

		foreach ( $composer_packages as $environment => $environment_packages ) {
			$dev = $environment === 'require-dev';

			foreach ( $environment_packages as $package_name => $version_constraint ) {
				if ( Composer::installed( $directory, $package_name ) ) {
					$output->writeln( '<failure>Composer package ' . $package_name . ' is already installed - skipped.</failure>' );
					continue;
				}

				Composer::install( $directory, $package_name, $version_constraint, $dev );
			}
		}
	}
}
