<?php

namespace WPEmerge\Cli\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\RuntimeException;
use WPEmerge\Cli\Presets\Bootstrap;
use WPEmerge\Cli\Presets\Bulma;
use WPEmerge\Cli\Presets\Foundation;
use WPEmerge\Cli\Presets\NormalizeCss;
use WPEmerge\Cli\Presets\Spectre;
use WPEmerge\Cli\Presets\Tachyons;
use WPEmerge\Cli\Presets\TailwindCss;

class InstallCssFramework extends Command {
	/**
	 * {@inheritDoc}
	 */
	protected function configure() {
		$this
			->setName( 'install:css-framework' )
			->setDescription( 'Install a CSS framework.' )
			->setHelp( 'Install a CSS framework from a list of options.' )
			->addArgument(
				'css-framework',
				InputArgument::REQUIRED,
				'CSS framework to install.'
			);
	}

	/**
	 * {@inheritDoc}
	 */
	protected function execute( InputInterface $input, OutputInterface $output ) {
		$css_framework = $input->getArgument( 'css-framework' );

		$preset = null;

		switch ( $css_framework ) {
			case 'Normalize.css':
				$preset = new NormalizeCss();
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

			case 'Tailwind CSS':
				$preset = new TailwindCss();
				break;

			case 'Spectre.css':
				$preset = new Spectre();
				break;

			default:
				throw new RuntimeException( 'Unknown css framework selected: ' . $css_framework );
				break;
		}

		if ( $preset === null ) {
			return;
		}

		$preset->execute( getcwd(), $output );
	}
}
