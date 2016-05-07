<?php
namespace palamon\tests;

use palamon\Requests\Grimoire;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class grimoireTestCase extends \PHPUnit_Framework_TestCase
{

	public function testGrimoireResponse()
    {
        
        $request = new Grimoire('7ced29b9f06844efb9102fbf73218362');
        $response = $request->getGrimoire();
        $this->assertArrayHasKey('Response', $response);

    }

}
