<?php

namespace App\Tests;

use WP_UnitTestCase;

/**
 * @coversDefaultClass \App\Controllers\Foo
 */
class ExampleTest extends WP_UnitTestCase {
	/**
	 * @covers ::foo
	 */
	public function testFoo() {
		$this->assertTrue( true );
	}
}
