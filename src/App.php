<?php

namespace WPEmerge\Cli;

use Composer\EventDispatcher\Event;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\BufferedOutput;
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
use WPEmerge\Cli\Commands\InstallGravityFormsUtilities;
use WPEmerge\Cli\Commands\InstallHtmlBurgerEnv;
use WPEmerge\Cli\Commands\InstallPhpTests;
use WPEmerge\Cli\Commands\MakeController;
use WPEmerge\Cli\Commands\MakeFacade;
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

		if ( ! static::isWordPressThemeDirectory( getcwd() ) ) {
			$application->renderException( new RuntimeException( 'Commands must be called from the root of a WordPress theme.' ), $output );
			return;
		}

		static::decorateOutput( $output );

		$application->run( $input, $output );
	}

	/**
	 * Run with the install command.
	 *
	 * @param  Event $event
	 * @return void
	 */
	public static function install( Event $event ) {
		$binary_name = 'wpemerge';
		$binary = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . $binary_name;

		$event->getIO()->write( '' );

		$process = new Process( $binary . ' install' );
		$process->setTimeout( null );

		try {
			$process->setTty( true );

			$process->run( function ( $type, $line ) use ( $event ) {
				$event->getIO()->write( $line );
			});
		} catch ( RuntimeException $e ) {
			$event->getIO()->write( '<error>' . $e->getMessage() . '</error>' );
			$event->getIO()->write( '' );
			$event->getIO()->write( 'Use <comment>./vendor/bin/' . $binary_name . ' install</comment> instead.' );
		}
	}

	/**
	 * Create a config.json in the theme root directory.
	 *
	 * @param  Event $event
	 * @return void
	 */
	public static function createConfigJson( Event $event ) {
		if ( ! defined( 'WPEMERGE_CLI_DIR' ) ) {
			define( 'WPEMERGE_CLI_DIR', dirname( __DIR__ ) );
		}

		$input = new ArrayInput( [ 'config:create' ] );
		$output = new BufferedOutput();

		try {
			$application = static::create();
			$command = $application->find('config:create');
			$command->run( $input, $output );
			$event->getIO()->write( $output->fetch() );
		} catch ( RuntimeException $e ) {
			$event->getIO()->write( '<error>' . $e->getMessage() . '</error>' );
		}
	}

	/**
	 * Install dependencies in the theme root directory.
	 *
	 * @param  Event $event
	 * @return void
	 */
	public static function installDependencies( Event $event ) {
		if ( ! defined( 'WPEMERGE_CLI_DIR' ) ) {
			define( 'WPEMERGE_CLI_DIR', dirname( __DIR__ ) );
		}

		$input = new ArrayInput( [ 'install:dependencies' ] );
		$output = new ConsoleOutput();

		try {
			$application = static::create();
			$command = $application->find('install:dependencies');
			$command->run( $input, $output );
		} catch ( RuntimeException $e ) {
			$event->getIO()->write( '<error>' . $e->getMessage() . '</error>' );
		}
	}

	/**
	 * Build assets in the theme root directory.
	 *
	 * @param  Event $event
	 * @return void
	 */
	public static function buildAssets( Event $event ) {
		if ( ! defined( 'WPEMERGE_CLI_DIR' ) ) {
			define( 'WPEMERGE_CLI_DIR', dirname( __DIR__ ) );
		}

		$input = new ArrayInput( [ 'assets:build' ] );
		$output = new ConsoleOutput();

		try {
			$application = static::create();
			$command = $application->find('assets:build');
			$command->run( $input, $output );
		} catch ( RuntimeException $e ) {
			$event->getIO()->write( '<error>' . $e->getMessage() . '</error>' );
		}
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
		$application->add( new InstallGravityFormsUtilities() );
		$application->add( new InstallHtmlBurgerEnv() );
		$application->add( new InstallPhpTests() );
		$application->add( new MakeController() );
		$application->add( new MakeFacade() );
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
	 * Check if a directory is a WordPress theme root.
	 *
	 * @param  string  $directory
	 * @return boolean
	 */
	protected static function isWordPressThemeDirectory( $directory ) {
		$composer = Composer::getComposerJson( $directory );

		if ( ! $composer ) {
			return false;
		}

		if ( $composer['type'] !== 'wordpress-theme' ) {
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
