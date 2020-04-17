<?php

namespace App\Tests;

use App;
use WP_UnitTestCase;

/**
 * @coversDefaultClass \App\Controllers\Web\ExampleController
 */
class ExampleTest extends WP_UnitTestCase {
	/**
	 * @var App
	 */
	public $app = null;

	/**
	 * Set up a new App instance to use for tests.
	 */
	public function setUp() {
		// Set up an App instance with whatever stubs and mocks we need before every test.
		$this->app = App::make();
		$this->app->bootstrap( [], false );

		// Since we don't want to test WP Emerge internals, we can overwrite them during testing:
		// $this->app->alias( 'view', function ( $view ) { return $view; } );
	}


	/**
	 * Tear down our test App instance.
	 */
	public function tearDown() {
		unset( $this->app );
	}

	/**
	 * @covers ::foo
	 */
	public function testFoo() {
		$this->assertTrue( true );
	}
}
