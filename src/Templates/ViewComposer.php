<?php

namespace WPEmerge\Cli\Templates;

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
		\$view->with( [
			'foo' => 'bar',
		] );
	}
}

EOT;

		return $this->storeOnDisc( $name, $namespace, $contents, $directory );
	}
}
