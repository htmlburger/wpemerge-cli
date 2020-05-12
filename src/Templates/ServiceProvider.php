<?php

namespace WPEmerge\Cli\Templates;

class ServiceProvider extends Template {
	/**
	 * {@inheritDoc}
	 */
	public function create( $name, $directory ) {
		$basename  = array_slice( explode( '\\', $name ), -1 )[0];
		$namespace = array_slice( explode( '\\', $name ), 0, -1 );
		$namespace = implode( '\\', $namespace );

		$contents = <<<EOT
<?php

namespace MyApp\\$namespace;

use WPEmerge\\ServiceProviders\ServiceProviderInterface;

/**
 * A service provider.
 */
class $basename implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( \$container ) {
		// Nothing to register.
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( \$container ) {
		// Nothing to bootstrap.
	}
}

EOT;

		return $this->storeOnDisc( $basename, $namespace, $contents, $directory );
	}
}
