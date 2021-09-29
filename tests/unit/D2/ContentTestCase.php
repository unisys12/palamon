<?php

namespace Palamon\Tests\Unit\D2;

use Palamon\Destiny2\DB\Content;
use PHPUnit\Framework\TestCase;

final class ContentTestCase extends TestCase
{
    public function testThatAPathWasPassed()
    {
        $conn = new Content("some/path");

        $this->assertEquals("some/path", $conn->path);
    }
}
