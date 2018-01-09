<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
		$clean_composer = $this->getApplication()->find( 'install:clean-composer');
		$install_carbon_fields = $this->getApplication()->find( 'install:carbon-fields');
		$install_graviry_forms_utilities = $this->getApplication()->find( 'install:gravity-forms-utilities' );

		$clean_composer->run( new ArrayInput( [
			'command' => $clean_composer->getName(),
		] ), $output );

		$install_carbon_fields->run( new ArrayInput( [
			'command' => $install_carbon_fields->getName(),
		] ), $output );

		$install_graviry_forms_utilities->run( new ArrayInput( [
			'command' => $install_graviry_forms_utilities->getName(),
		] ), $output );
	}
}
