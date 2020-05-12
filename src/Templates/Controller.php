<?php

namespace WPEmerge\Cli\Templates;

class Controller extends Template {
	/**
	 * {@inheritDoc}
	 */
	public function create( $name, $directory ) {
		$basename  = array_slice( explode( '\\', $name ), -1 )[0];
		$namespace = array_slice( explode( '\\', $name ), 0, -1 );
		$namespace = array_merge( ['Controllers'], $namespace );
		$namespace = implode( '\\', $namespace );

		$contents = <<<EOT
<?php

namespace MyApp\\$namespace;

use WPEmerge\\Requests\\Request;
use WPEmerge\\View\\ViewInterface;

class $basename {
	/**
	 * Handle the index page.
	 *
	 * @param Request        \$request
	 * @param string         \$view
	 * @return ViewInterface
	 */
	public function index( Request \$request, \$view ) {
		// Add back-end logic here
		// for example, prepare some variables to pass to the view
		// or validate request parameters etc.
		\$foo = 'foobar';

		return \\MyApp::view( \$view )
			->with( [
				'foo' => \$foo,
			] );
	}
}

EOT;

		return $this->storeOnDisc( $basename, $namespace, $contents, $directory );
	}
}
