<?php

namespace Palamon\Tests\Unit;

use Palamon\Enums;
use PHPUnit\Framework\TestCase;

final class enumsTestCase extends TestCase
{
    public function testBaseUrl()
    {
        $this->assertEquals("https://www.bungie.net/", Enums::baseUrl());
    }
}
