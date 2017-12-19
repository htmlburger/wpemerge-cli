<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\Composer\Composer;
use WPEmerge\Cli\Helpers\Boolean;
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
			->setHelp( 'Provides a number of choices on how to decorate your WP Emerge Theme.' )
			->addOption(
				'install-carbon-fields',
				null,
				InputOption::VALUE_REQUIRED,
				'Flag whether to install Carbon Fields or not.'
			)
			->addOption(
				'install-css-framework',
				null,
				InputOption::VALUE_REQUIRED,
				'CSS framework to install, if any.'
			)
			->addOption(
				'install-font-awesome',
				null,
				InputOption::VALUE_REQUIRED,
				'Flag whether to install Font Awesome or not.'
			);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$this->removeComposerAuthorInformation( $input, $output );

		$this->maybeInstallCarbonFields( $input, $output );

		$this->maybeInstallCssFramework( $input, $output );

		$this->maybeInstallFontAwesome( $input, $output );
	}

	/**
	 * Remove author information from composer.json
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function removeComposerAuthorInformation( InputInterface $input, OutputInterface $output ) {
		$composer = Composer::getComposerJson( getcwd() );

		unset( $composer['name'] );
		unset( $composer['description'] );
		unset( $composer['homepage'] );
		unset( $composer['authors'] );

		Composer::storeComposerJson( $composer, getcwd() );
	}

	/**
	 * Maybe install Carbon Fields
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function maybeInstallCarbonFields( InputInterface $input, OutputInterface $output ) {
		$install = $input->getOption( 'install-carbon-fields' );

		if ( $install === null ) {
			$helper = $this->getHelper( 'question' );

			$question = new ConfirmationQuestion(
				'Would you like to install Carbon Fields? <info>[y/N]</info> ',
				false
			);

			$install = $helper->ask( $input, $output, $question );
		} else {
			$install = Boolean::fromString( $install );
		}

		if ( $install ) {
			$this->installCarbonFields( $input, $output );
		}
	}

	/**
	 * Install Carbon Fields
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function installCarbonFields( InputInterface $input, OutputInterface $output ) {
		// TODO install carbon fields
		$output->writeln( '' );
	}

	/**
	 * Maybe install any CSS framework
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function maybeInstallCssFramework( InputInterface $input, OutputInterface $output ) {
		$css_framework = $input->getOption( 'install-css-framework' );

		if ( $css_framework === null ) {
			$helper = $this->getHelper( 'question' );

			$question = new ChoiceQuestion(
				'Please select a CSS framework:',
				['None', 'Bootstrap', 'Bulma', 'Foundation', 'Tachyons'],
				0
			);

			$css_framework = $helper->ask( $input, $output, $question );
		}

		$this->installCssFramework( $input, $output, $css_framework );
	}

	/**
	 * Install any CSS framework
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @param  string          $css_framework
	 * @return void
	 */
	protected function installCssFramework( InputInterface $input, OutputInterface $output, $css_framework ) {
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

			default:
				throw new RuntimeException( 'Unknown css framework selected: ' . $css_framework );
				break;
		}

		if ( $preset === null ) {
			return;
		}

		$this->installPreset( $preset, $output );
	}

	/**
	 * Maybe install Font Awesome
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function maybeInstallFontAwesome( InputInterface $input, OutputInterface $output ) {
		$install = $input->getOption( 'install-font-awesome' );

		if ( $install === null ) {
			$helper = $this->getHelper( 'question' );

			$question = new ConfirmationQuestion(
				'Would you like to install Font Awesome? <info>[y/N]</info> ',
				false
			);

			$install = $helper->ask( $input, $output, $question );
		} else {
			$install = Boolean::fromString( $install );
		}

		if ( $install ) {
			$this->installFontAwesome( $input, $output );
		}
	}

	/**
	 * Install Font Awesome
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function installFontAwesome( InputInterface $input, OutputInterface $output ) {
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
