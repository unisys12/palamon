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
        
        $mock = new MockHandler([
            new Response(200, ['X-Ventcore-Status' => '1'])
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $statusCodeCheck = $client->request('GET', 'http://www.bungie.net/Platform/Destiny/Vanguard/Grimoire/Definition/')->getStatusCode();

        $this->assertEquals('200', $statusCodeCheck);

    }

}
