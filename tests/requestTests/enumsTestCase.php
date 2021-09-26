<?php

namespace Palamon\Tests;

use Palamon\Enums as Enums;
use PHPUnit\Framework\TestCase;

// use PHPUnit\Framework\TestCase;

// class EnumsTestCase extends TestCase
// {
//     public function testBaseUrl()
//     {
//         $this->assertEquals("https://www.bungie.net/", \Palamon\Enums::baseUrl());
//     }
// }

class enumsTestCase extends TestCase
{
    public function testBaseUrl()
    {
        $this->assertEquals("https://www.bungie.net/", Enums::baseUrl());
    }
}
