<?php

namespace WPEmerge\Cli;

use Composer\EventDispatcher\Event;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use WPEmerge\Cli\Commands\Install;
use WPEmerge\Cli\Commands\MakeController;
use WPEmerge\Cli\Commands\MakeFacade;
use WPEmerge\Cli\Commands\MakeViewComposer;
use WPEmerge\Cli\Composer\Composer;

class App {
	/**
	 * Run the application
	 *
	 * @return void
	 */
	public static function run( Input $input = null, Output $output = null ) {
		global $argv;

		$composer = Composer::getComposerJson( WPEMERGE_CLI_DIR );

		$application = new Application( 'WPEmerge CLI', $composer['version'] );

		$application->add( new Install() );
		$application->add( new MakeController() );
		$application->add( new MakeFacade() );
		$application->add( new MakeViewComposer() );

		if ( $input === null ) {
			$input = new ArgvInput( array_slice( $argv, 0 ) );
		}

		if ( $output === null ) {
			$output = new ConsoleOutput();
		}

		$output->writeln( '' );

		if ( ! static::isWordPressThemeDirectory( getcwd() ) ) {
			$output->writeln( '<error>Commands must be called from the root of a WordPress theme.</error>');
			return;
		}

		$application->run( $input, $output );
	}

	/**
	 * Run with the install-theme command
	 *
	 * @return void
	 */
	public static function installTheme( Event $event ) {
		$binary_name = 'wpemerge';
		$binary = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . $binary_name;

		$event->getIO()->write( '' );

		$process = (new ProcessBuilder())
			->setTimeout( null )
			->setPrefix( 'php' )
			->setArguments( [ $binary, 'install-theme' ] )
			->getProcess();

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
	 * Check if a directory is a WordPress theme root
	 *
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
	 * Run a shell command
	 *
	 * @param  string      $command
	 * @param  string|null $directory
	 * @return string
	 */
	public static function execute( $command, $directory = null ) {
		$directory = $directory !== null ? $directory : getcwd();

		$process = new Process( $command );
		$process->setWorkingDirectory( $directory );
		$process->run();

		if ( ! $process->isSuccessful() ) {
			throw new ProcessFailedException( $process );
		}

		return $process->getOutput();
	}
}
