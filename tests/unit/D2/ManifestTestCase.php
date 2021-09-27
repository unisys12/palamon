<?php

namespace Palamon\Tests\Unit\D2;

use Palamon\Destiny2\Requests\Manifest;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertCount;

final class ManifestTestCase extends TestCase
{
    protected function setUp(): void
    {
        $this->manifest = new Manifest("en");
    }

    public function testManifestIsReturned(): void
    {
        $response = $this->manifest->getManifest();

        $this->assertCount("6", $response);
        $this->assertArrayHasKey("Response", $response);
    }

    public function testGetVersionReturnsAVersion()
    {
        $this->assertIsString($this->manifest->getVersion());
    }

    public function testJsonWorldContentPathsShouldReturnAString()
    {
        $this->assertIsString($this->manifest->getJsonWorldContentPaths());
    }
}
