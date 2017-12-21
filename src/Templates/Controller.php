<?php

namespace WPEmerge\Cli\Templates;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class Controller extends Template {
	/**
	 * {@inheritDoc}
	 */
	public function create( $name, $directory ) {
		$namespace = 'Controllers';

		$contents = <<<EOT
<?php

namespace App\\$namespace;

use WPEmerge\Request;

class $name {
	public function index( Request \$request, \$view ) {
		// Add back-end logic here
		// for example, prepare some variables to pass to the view
		// or validate request parameters etc.
		\$foo = 'foobar';

		return wpm_view( \$view, [
			'foo' => \$foo,
		] );
	}
}

EOT;

		return $this->storeOnDisc( $name, $namespace, $contents, $directory );
	}
}
