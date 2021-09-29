<?php

namespace Palamon\Tests\Unit\D2;

use Palamon\Destiny2\Requests\Manifest;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertCount;

final class ManifestTestCase extends TestCase
{

    public function testManifestIsReturned(): void
    {
        $manifest = new Manifest("en");
        $response = $manifest->getManifest();

        $this->assertCount("6", $response);
        $this->assertArrayHasKey("Response", $response);
    }

    public function testGetVersionReturnsAVersion()
    {
        $manifest = new Manifest("en");
        $this->assertIsString($manifest->getVersion());
    }

    public function testJsonWorldContentPathsShouldReturnAString()
    {
        $manifest = new Manifest("en");
        $this->assertIsString($manifest->getJsonWorldContentPaths());
    }
}
