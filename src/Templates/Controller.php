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

namespace MyTheme\\$namespace;

use WPEmerge\\Requests\\Request;

class $basename {
	public function index( Request \$request, \$view ) {
		// Add back-end logic here
		// for example, prepare some variables to pass to the view
		// or validate request parameters etc.
		\$foo = 'foobar';

		return \\WPEmerge\\view( \$view )
			->with( [
				'foo' => \$foo,
			] );
	}
}

EOT;

		return $this->storeOnDisc( $basename, $namespace, $contents, $directory );
	}
}
