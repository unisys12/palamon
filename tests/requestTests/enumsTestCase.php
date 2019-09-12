<?php

namespace palamon\Tests;

use palamon\Enums as Enums;
// use PHPUnit\Framework\TestCase;

// class EnumsTestCase extends TestCase
// {
//     public function testBaseUrl()
//     {
//         $this->assertEquals("https://www.bungie.net/", \Palamon\Enums::baseUrl());
//     }
// }

class enumsTestCase extends \PHPUnit_Framework_TestCase
{
    public function testBaseUrl()
    {
        $this->assertEquals("https://www.bungie.net/", Enums::baseUrl());
    }
}