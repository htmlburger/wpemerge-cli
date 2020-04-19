<?php

namespace App\Tests;

use App;
use WP_UnitTestCase;

/**
 * @coversDefaultClass \App\Controllers\Web\ExampleController
 */
class ExampleTest extends WP_UnitTestCase {
	/**
	 * Set up a new App instance to use for tests.
	 */
	public function setUp() {
		// Set up an App instance with whatever stubs and mocks we need before every test.
		App::make()->bootstrap( [], false );

		// Since we don't want to test WP Emerge internals, we can overwrite them during testing:
		// App::alias( 'view', function ( $view ) { return $view; } );

		// or we can replace the entire app instance:
		// App::setApplication( new MyMockApplication() );
	}


	/**
	 * Tear down our test App instance.
	 */
	public function tearDown() {
		App::setApplication( null );
	}

	/**
	 * @covers ::foo
	 */
	public function testFoo() {
		$this->assertTrue( true );
	}
}
