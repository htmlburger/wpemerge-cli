<?php

namespace WPEmerge\Cli\Templates;

class Controller extends Template {
	/**
	 * {@inheritDoc}
	 */
	public function create( $name, $directory ) {
		$namespace = 'Controllers';

		$contents = <<<EOT
<?php

namespace App\\$namespace;

use WPEmerge\Requests\Request;

class $name {
	public function index( Request \$request, \$view ) {
		// Add back-end logic here
		// for example, prepare some variables to pass to the view
		// or validate request parameters etc.
		\$foo = 'foobar';

		return \WPEmerge\view( \$view )
			->with( [
				'foo' => \$foo,
			] );
	}
}

EOT;

		return $this->storeOnDisc( $name, $namespace, $contents, $directory );
	}
}
