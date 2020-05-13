<?php

namespace WPEmerge\Cli;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;
use WPEmerge\Cli\Commands\AssetsBuild;
use WPEmerge\Cli\Commands\CreateConfigJson;
use WPEmerge\Cli\Commands\Install;
use WPEmerge\Cli\Commands\InstallCarbonFields;
use WPEmerge\Cli\Commands\InstallCleanComposer;
use WPEmerge\Cli\Commands\InstallCssFramework;
use WPEmerge\Cli\Commands\InstallDependencies;
use WPEmerge\Cli\Commands\InstallFontAwesome;
use WPEmerge\Cli\Commands\InstallPhpTests;
use WPEmerge\Cli\Commands\MakeController;
use WPEmerge\Cli\Commands\MakeServiceProvider;
use WPEmerge\Cli\Commands\MakeViewComposer;
use WPEmerge\Cli\Composer\Composer;

class App {
	/**
	 * Run the application.
	 *
	 * @param  Input|null  $input
	 * @param  Output|null $output
	 * @return void
	 */
	public static function run( Input $input = null, Output $output = null ) {
		global $argv;

		$application = static::create();

		if ( $input === null ) {
			$input = new ArgvInput( array_slice( $argv, 0 ) );
		}

		if ( $output === null ) {
			$output = new ConsoleOutput();
		}

		if ( ! static::isValidDirectory( getcwd() ) ) {
			$application->renderException( new RuntimeException( 'Commands must be called from the root of a WordPress theme or plugin.' ), $output );
			return;
		}

		static::decorateOutput( $output );

		$application->run( $input, $output );
	}

	/**
	 * Create the application.
	 *
	 * @return Application
	 */
	public static function create() {
		$composer = Composer::getComposerJson( WPEMERGE_CLI_DIR );

		$application = new Application( 'WPEmerge CLI', $composer['version'] );

		$application->add( new AssetsBuild() );
		$application->add( new CreateConfigJson() );
		$application->add( new Install() );
		$application->add( new InstallCarbonFields() );
		$application->add( new InstallCleanComposer() );
		$application->add( new InstallCssFramework() );
		$application->add( new InstallDependencies() );
		$application->add( new InstallFontAwesome() );
		$application->add( new InstallPhpTests() );
		$application->add( new MakeController() );
		$application->add( new MakeServiceProvider() );
		$application->add( new MakeViewComposer() );

		return $application;
	}

	/**
	 * Decorate output object.
	 *
	 * @param  OutputInterface $output
	 * @return void
	 */
	protected static function decorateOutput( OutputInterface $output ) {
		$output->getFormatter()->setStyle( 'failure', new OutputFormatterStyle( 'red' ) );
	}

	/**
	 * Check if a directory is a valid WordPress theme or plugin root.
	 *
	 * @param  string  $directory
	 * @return boolean
	 */
	protected static function isValidDirectory( $directory ) {
		$composer = Composer::getComposerJson( $directory );

		if ( ! $composer ) {
			return false;
		}

		$type = isset( $composer['type'] ) ? $composer['type'] : '';

		if ( $type !== 'wordpress-theme' && $type !== 'wordpress-plugin' ) {
			return false;
		}

		return true;
	}

	/**
	 * Run a shell command.
	 *
	 * @param  string      $command
	 * @param  string|null $directory
	 * @param  integer     $timeout
	 * @return string
	 */
	public static function execute( $command, $directory = null, $timeout = 120 ) {
		$directory = $directory !== null ? $directory : getcwd();

		$process = new Process( $command, null, null, null, $timeout );
		$process->setWorkingDirectory( $directory );
		$process->mustRun();

		return $process->getOutput();
	}

	/**
	 * Run a shell command and return the output as it comes in.
	 *
	 * @param  string          $command
	 * @param  OutputInterface $output
	 * @param  string|null     $directory
	 * @param  integer         $timeout
	 * @return Process
	 */
	public static function liveExecute( $command, OutputInterface $output, $directory = null, $timeout = 120 ) {
		$directory = $directory !== null ? $directory : getcwd();

		$process = new Process( $command, null, null, null, $timeout );
		$process->setWorkingDirectory( $directory );
		$process->start();

		$process->wait( function( $type, $buffer ) use ( $output ) {
			$output->writeln( $buffer );
		} );

		if ( ! $process->isSuccessful() ) {
			throw new ProcessFailedException( $process );
		}

		return $process;
	}
}
