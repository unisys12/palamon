<?php

use palamon\Requests\Grimoire;

class grimoireTestCase extends PHPUnit_Framework_TestCase{

	public function testGrimoireInstance()
	{
		//Test if class can be created
		$grimoire = new Grimoire();
		$this->assertNotFalse($grimoire);
	}

	public function testGrimoireResponse()
	{
		$grimoire = new Grimoire();

		$request = $grimoire->getGrimoire();
		$this->assertTrue($request);
		$this->assertIsArray($request);
	}

}
