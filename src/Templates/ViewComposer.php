<?php

namespace WPEmerge\Cli\Templates;

use Camel\CaseTransformer;
use Camel\Format;
use Symfony\Component\Console\Exception\InvalidArgumentException;

class ViewComposer extends Template {
	/**
	 * {@inheritDoc}
	 */
	public function create( $name, $directory ) {
		$namespace = 'ViewComposers';

		$contents = <<<EOT
<?php

namespace App\\$namespace;

class $name {
	public function compose( \$view ) {
		return [
			'foo' => 'bar',
		];
	}
}

EOT;

		return $this->storeOnDisc( $name, $namespace, $contents, $directory );
	}
}
