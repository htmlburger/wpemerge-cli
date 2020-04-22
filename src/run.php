<?php
if ( php_sapi_name() !== 'cli' ) {
	exit;
}

$autoloaders = [
	dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
	dirname( dirname( dirname( __DIR__ ) ) ) . DIRECTORY_SEPARATOR . 'autoload.php',
	dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
	getcwd() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
];

foreach ( $autoloaders as $autoloader ) {
	if ( file_exists( $autoloader ) ) {
		require_once $autoloader;
		break;
	}
}

\WPEmerge\Cli\App::run();
