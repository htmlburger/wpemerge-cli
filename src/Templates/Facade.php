<?php

namespace WPEmerge\Cli\Templates;

class Facade extends Template {
	/**
	 * {@inheritDoc}
	 */
	public function create( $name, $directory ) {
		$namespace = 'Facades';

		$contents = <<<EOT
<?php

namespace App\\$namespace;

use WPEmerge\Support\Facade;

class $name extends Facade {
	protected static function getFacadeAccessor() {
		return 'YOUR_CONTAINER_IDENTIFIER_HERE';
	}
}

EOT;

		return $this->storeOnDisc( $name, $namespace, $contents, $directory );
	}
}
