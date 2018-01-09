<?php

namespace App\Tests;

use WP_UnitTestCase;

/**
 * @coversDefaultClass \App\Controllers\Foo
 */
class UrlTest extends WP_UnitTestCase {
	/**
	 * @covers ::foo
	 */
	public function testFoo() {
		$this->assertEquals( true, true );
	}
}
