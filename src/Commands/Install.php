<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use WPEmerge\Cli\Presets\Bootstrap;
use WPEmerge\Cli\Presets\Bulma;
use WPEmerge\Cli\Presets\FontAwesome;
use WPEmerge\Cli\Presets\Foundation;
use WPEmerge\Cli\Presets\PresetInterface;
use WPEmerge\Cli\Presets\Tachyons;

class Install extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install-theme' )
			->setDescription( 'Interactively enables theme options.' )
			->setHelp( 'Provides a number of choices on how to decorate your WP Emerge Theme.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$this->installCarbonFields( $input, $output );
		$output->writeln( '' );
		$this->installCssFramework( $input, $output );
		$output->writeln( '' );
		$this->installFontAwesome( $input, $output );
		$output->writeln( '' );
	}

	/**
	 * Ask whether to install Carbon Fields
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function installCarbonFields( InputInterface $input, OutputInterface $output ) {
		$helper = $this->getHelper( 'question' );

		$question = new ConfirmationQuestion(
			'Would you like to install Carbon Fields? <info>[y/N]</info> ',
			false
		);

		if ( ! $helper->ask( $input, $output, $question ) ) {
			return;
		}

		// TODO install carbon fields
	}

	/**
	 * Ask whether to install any CSS framework
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function installCssFramework( InputInterface $input, OutputInterface $output ) {
		$helper = $this->getHelper( 'question' );

		$question = new ChoiceQuestion(
			'Please select a CSS framework:',
			['None', 'Bootstrap', 'Bulma', 'Foundation', 'Tachyons'],
			0
		);

		$css_framework = $helper->ask( $input, $output, $question );

		$preset = null;
		switch ( $css_framework ) {
			case 'None':
				// nothing to do
				break;

			case 'Bootstrap':
				$preset = new Bootstrap();
				break;

			case 'Bulma':
				$preset = new Bulma();
				break;

			case 'Foundation':
				$preset = new Foundation();
				break;

			case 'Tachyons':
				$preset = new Tachyons();
				break;
		}

		if ( $preset === null ) {
			return;
		}

		$this->installPreset( $preset, $output );
	}

	/**
	 * Ask whether to install Font Awesome
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function installFontAwesome( InputInterface $input, OutputInterface $output ) {
		$helper = $this->getHelper( 'question' );

		$question = new ConfirmationQuestion(
			'Would you like to install Font Awesome? <info>[y/N]</info> ',
			false
		);

		if ( ! $helper->ask( $input, $output, $question ) ) {
			return;
		}

		$this->installPreset( new FontAwesome(), $output );
	}

	/**
	 * Install a preset
	 *
	 * @param  PresetInterface $preset
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function installPreset( PresetInterface $preset, OutputInterface $output ) {
		$output->writeln( '' );
		$output->write( '<comment>Installing <info>' . $preset->getName() . '</info> ...</comment>' );
		$preset_output = $preset->execute( getcwd() );
		$output->writeln( ' <info>Done</info>' );

		$output->writeln( '---' );
		$output->writeln( $preset_output );
		$output->writeln( '---' );
	}
}
