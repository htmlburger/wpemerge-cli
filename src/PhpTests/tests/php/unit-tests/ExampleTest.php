<?php

namespace MyTheme\Tests;

use WP_UnitTestCase;

/**
 * @coversDefaultClass \MyTheme\Controllers\Web\ExampleController
 */
class ExampleTest extends WP_UnitTestCase {
	/**
	 * Set up a new app instance to use for tests.
	 */
	public function setUp() {
		// Set up an app instance with whatever stubs and mocks we need before every test.
		\MyTheme::make()->bootstrap( [], false );

		// Since we don't want to test WP Emerge internals, we can overwrite them during testing:
		// \MyTheme::alias( 'view', function ( $view ) { return $view; } );

		// or we can replace the entire app instance:
		// \MyTheme::setApplication( new MyMockApplication() );
	}


	/**
	 * Tear down our test app instance.
	 */
	public function tearDown() {
		\MyTheme::setApplication( null );
	}

	/**
	 * @covers ::foo
	 */
	public function testFoo() {
		$this->assertTrue( true );
	}
}
