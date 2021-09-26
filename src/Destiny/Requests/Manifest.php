<?php

namespace Palamon\Destiny\Requests;

use \ZipArchive;

class Manifest
{

    const MANIFEST_URL = "http://www.bungie.net/Platform/Destiny/Manifest/";

    public function checkManifest()
    {

        $client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
        $req = $client->request('GET', self::MANIFEST_URL);
        $res = json_decode($req->getBody(), true);

        $version = $res['Response']['version'];

        return $version;
    }

    public function getManifest()
    {

        $client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
        $req = $client->get(self::MANIFEST_URL, ['verify' => false]);
        $res = json_decode($req->getBody(), true);

        return $res;
    }

    public function downloadMobileWorldContents()
    {

        // Retrieve Updated Bungie Destiny Manifest
        $client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
        $req = $client->get(self::MANIFEST_URL, ['verify' => false]);
        $res = json_decode($req->getBody(), true);

        // Retrieve path to updated contents path
        $contentpath = $res['Response']['mobileWorldContentPaths']['en'];

        // Perform call on new content path and return the sqlite data
        $client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
        $req = $client->get('http://www.bungie.net' . $contentpath, ['verify' => false]);
        $result = $req->getBody();

        // Create zip archive to store data file
        $zip = new ZipArchive;
        $manifest = $zip->open('manifest' . date('mdY') . '.zip', ZipArchive::CREATE);
        file_put_contents($manifest, $result);

        if ($zip->open($manifest) === TRUE) {
            $zip->extractTo('./storage/destiny1/'); // update to directory outside of package
            $zip->close();
            unlink($manifest);
        } else {
            echo "Error putting contents in ZipArchive";
        }
    }
}
