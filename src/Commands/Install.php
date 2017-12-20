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
use WPEmerge\Cli\Presets\CarbonFields;
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
				'confirm',
				null,
				InputOption::VALUE_REQUIRED,
				'Flag whether to ask for confirmation in interactive mode or not.',
				true
			)
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
		$config = [
			'remove_composer_author_information' => true,
			'install_carbon_fields' => false,
			'install_css_framework' => 'None',
			'install_font_awesome' => false,
		];

		$config[ 'install_carbon_fields' ] = $this->shouldInstallCarbonFields( $input, $output );
		$config[ 'install_css_framework' ] = $this->shouldInstallCssFramework( $input, $output );
		$config[ 'install_font_awesome' ] = $this->shouldInstallFontAwesome( $input, $output );

		$confirm = Boolean::fromString( $input->getOption( 'confirm' ) );
		var_dump($confirm);

		if ( $confirm && $input->isInteractive() ) {
			$helper = $this->getHelper( 'question' );

			$output->writeln( 'Configuration:' );
			$output->writeln(
				str_pad( 'Install Carbon Fields: ', 25 )  .
				( $config['install_carbon_fields'] ? '<info>Yes</info>' : '<comment>No</comment>' )
			);
			$output->writeln(
				str_pad( 'Install CSS Framework: ', 25 ) .
				'<info>' . $config['install_css_framework'] . '</info>'
			);
			$output->writeln(
				str_pad( 'Install Font Awesome: ', 25 ) .
				( $config['install_font_awesome'] ? '<info>Yes</info>' : '<comment>No</comment>' )
			);
			$output->writeln( '' );

			$question = new ConfirmationQuestion(
				'Proceed with installation? <info>[Y/n]</info> ',
				true
			);

			$proceed = $helper->ask( $input, $output, $question );
			$output->writeln( '' );

			if ( ! $proceed ) {
				$output->writeln( '<comment>Installation aborted.</comment>' );
				return;
			}
		}

		if ( $config['remove_composer_author_information'] ) {
			$this->removeComposerAuthorInformation( $input, $output );
		}

		if ( $config['install_carbon_fields'] ) {
			$this->installCarbonFields( $input, $output );
		}

		if ( $config['install_css_framework'] ) {
			$this->installCssFramework( $input, $output, $config['install_css_framework'] );
		}

		if ( $config['install_font_awesome'] ) {
			$this->installFontAwesome( $input, $output );
		}
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
	 * Check whether Carbon Fields should be installed
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return boolean
	 */
	protected function shouldInstallCarbonFields( InputInterface $input, OutputInterface $output ) {
		$install = $input->getOption( 'install-carbon-fields' );

		if ( $install === null ) {
			if ( ! $input->isInteractive() ) {
				$install = false;
			} else {
				$helper = $this->getHelper( 'question' );

				$question = new ConfirmationQuestion(
					'Would you like to install Carbon Fields? <info>[y/N]</info> ',
					false
				);

				$install = $helper->ask( $input, $output, $question );
				$output->writeln( '' );
			}
		} else {
			$install = Boolean::fromString( $install );
		}

		return $install;
	}

	/**
	 * Install Carbon Fields
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function installCarbonFields( InputInterface $input, OutputInterface $output ) {
		$this->installPreset( new CarbonFields(), $output );
	}

	/**
	 * Check whether any CSS framework should be installed
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return string
	 */
	protected function shouldInstallCssFramework( InputInterface $input, OutputInterface $output ) {
		$css_framework = $input->getOption( 'install-css-framework' );

		if ( $css_framework === null ) {
			if ( ! $input->isInteractive() ) {
				$css_framework = 'None';
			} else {
				$helper = $this->getHelper( 'question' );

				$question = new ChoiceQuestion(
					'Please select a CSS framework:',
					['None', 'Bootstrap', 'Bulma', 'Foundation', 'Tachyons'],
					0
				);

				$css_framework = $helper->ask( $input, $output, $question );
				$output->writeln( '' );
			}
		}

		return $css_framework;
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
	 * Check whether Font Awesome should be installed
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return boolean
	 */
	protected function shouldInstallFontAwesome( InputInterface $input, OutputInterface $output ) {
		$install = $input->getOption( 'install-font-awesome' );

		if ( $install === null ) {
			if ( ! $input->isInteractive() ) {
				$install = false;
			} else {
				$helper = $this->getHelper( 'question' );

				$question = new ConfirmationQuestion(
					'Would you like to install Font Awesome? <info>[y/N]</info> ',
					false
				);

				$install = $helper->ask( $input, $output, $question );
				$output->writeln( '' );
			}
		} else {
			$install = Boolean::fromString( $install );
		}

		return $install;
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
		$output->write( '<comment>Installing <info>' . $preset->getName() . '</info> ...</comment>' );
		$preset_output = $preset->execute( getcwd() );
		$output->writeln( ' <info>Done</info>' );

		if ( ! empty( $preset_output ) ) {
			$output->writeln( '---' );
			$output->writeln( $preset_output );
			$output->writeln( '---' );
		}
	}
}
