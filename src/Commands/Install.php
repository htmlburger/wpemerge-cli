<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\Helpers\Boolean;

class Install extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install' )
			->setDescription( 'Interactively install options.' )
			->setHelp( 'Provides a number of choices on how to decorate your WP Emerge project.' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		if ( ! $input->isInteractive() ) {
			throw new RuntimeException( 'The install command requires an interactive session.' );
		}

		$config = [
			'remove_composer_author_information' => $this->shouldRemoveComposerAuthorInformation( $input, $output ),
			'install_carbon_fields' => $this->shouldInstallCarbonFields( $input, $output ),
			'install_css_framework' => $this->shouldInstallCssFramework( $input, $output ),
			'install_font_awesome' => $this->shouldInstallFontAwesome( $input, $output ),
		];

		$proceed = $this->shouldProceedWithInstall( $input, $output, $config );

		if ( ! $proceed ) {
			throw new RuntimeException( 'Install aborted.' );
		}

		if ( $config['remove_composer_author_information'] ) {
			$this->removeComposerAuthorInformation( $input, $output );
		}

		if ( $config['install_carbon_fields'] ) {
			$this->installCarbonFields( $input, $output );
		}

		if ( $config['install_css_framework'] !== 'None' ) {
			$this->installCssFramework( $input, $output, $config['install_css_framework'] );
		}

		if ( $config['install_font_awesome'] ) {
			$this->installFontAwesome( $input, $output );
		}
	}

	/**
	 * Check whether composer.json should be cleaned of author information
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return boolean
	 */
	protected function shouldRemoveComposerAuthorInformation( InputInterface $input, OutputInterface $output ) {
		$helper = $this->getHelper( 'question' );

		$question = new ConfirmationQuestion(
			'Would you like to remove author information from composer.json? <info>[y/N]</info> ',
			false
		);

		$install = $helper->ask( $input, $output, $question );
		$output->writeln( '' );

		return $install;
	}

	/**
	 * Remove author information from composer.json
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function removeComposerAuthorInformation( InputInterface $input, OutputInterface $output ) {
		$command = $this->getApplication()->find( 'install:clean-composer' );

		$output->write( '<comment>Cleaning <info>composer.json</info> ...</comment>' );

		$command->run( new ArrayInput( [
			'command' => 'install:carbon-fields',
		] ), new NullOutput() );

		$output->writeln( ' <info>Done</info>' );
	}

	/**
	 * Check whether Carbon Fields should be installed
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return boolean
	 */
	protected function shouldInstallCarbonFields( InputInterface $input, OutputInterface $output ) {
		$helper = $this->getHelper( 'question' );

		$question = new ConfirmationQuestion(
			'Would you like to install Carbon Fields? <info>[y/N]</info> ',
			false
		);

		$install = $helper->ask( $input, $output, $question );
		$output->writeln( '' );

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
		$command = $this->getApplication()->find( 'install:carbon-fields' );

		$this->runInstallCommand( 'Carbon Fields', $command, new ArrayInput( [
			'command' => 'install:carbon-fields',
		] ), $output );
	}

	/**
	 * Check whether any CSS framework should be installed
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return string
	 */
	protected function shouldInstallCssFramework( InputInterface $input, OutputInterface $output ) {
		$helper = $this->getHelper( 'question' );

		$question = new ChoiceQuestion(
			'Please select a CSS framework:',
			['None', 'Normalize.css', 'Bootstrap', 'Bulma', 'Foundation', 'Tachyons', 'Tailwind CSS', 'Spectre.css'],
			0
		);

		$css_framework = $helper->ask( $input, $output, $question );
		$output->writeln( '' );

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
		$command = $this->getApplication()->find( 'install:css-framework' );

		$this->runInstallCommand( $css_framework, $command, new ArrayInput( [
			'command' => 'install:css-framework',
			'css-framework' => $css_framework,
		] ), $output );
	}

	/**
	 * Check whether Font Awesome should be installed
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return boolean
	 */
	protected function shouldInstallFontAwesome( InputInterface $input, OutputInterface $output ) {
		$helper = $this->getHelper( 'question' );

		$question = new ConfirmationQuestion(
			'Would you like to install Font Awesome? <info>[y/N]</info> ',
			false
		);

		$install = $helper->ask( $input, $output, $question );
		$output->writeln( '' );

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
		$command = $this->getApplication()->find( 'install:font-awesome' );

		$this->runInstallCommand( 'Font Awesome', $command, new ArrayInput( [
			'command' => 'install:font-awesome',
		] ), $output );
	}

	/**
	 * Check whether we should proceed with installation
	 *
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @param  array           $config
	 * @return boolean
	 */
	protected function shouldProceedWithInstall( InputInterface $input, OutputInterface $output, $config ) {
		$helper = $this->getHelper( 'question' );

		$output->writeln( 'Configuration:' );

		$output->writeln(
			str_pad( 'Clean composer.json: ', 25 )  .
			( $config['remove_composer_author_information'] ? '<info>Yes</info>' : '<comment>No</comment>' )
		);

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

		return $proceed;
	}

	/**
	 * Install a preset
	 *
	 * @param  string          $label
	 * @param  Command         $command
	 * @param  InputInterface  $input
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected function runInstallCommand( $label, Command $command, InputInterface $input, OutputInterface $output ) {
		$buffered_output = new BufferedOutput();
		$buffered_output->setVerbosity( $output->getVerbosity() );

		$output->write( '<comment>Installing <info>' . $label . '</info> ...</comment>' );
		$command->run( $input, $buffered_output );
		$output->writeln( ' <info>Done</info>' );

		$buffered_output_value = $buffered_output->fetch();
		if ( ! empty( $buffered_output_value ) ) {
			$output->writeln( '---' );
			$output->writeln( trim( $buffered_output_value ) );
			$output->writeln( '---' );
		}
	}
}
