<?php

use palamon\Requests\Grimoire;

class grimoireTestCase extends PHPUnit_Framework_TestCase{

	public function testGrimoireInstance()
	{
		$grimoire = new Grimoire();
		$this->assertNotFalse($grimoire);
	}

}
