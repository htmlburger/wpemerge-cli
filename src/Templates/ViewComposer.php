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

namespace MyApp\\$namespace;

use WPEmerge\\View\\ViewInterface;

class $name {
	/**
	 * Compose a view.
	 *
	 * @param  ViewInterface \$view
	 * @return void
	 */
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
