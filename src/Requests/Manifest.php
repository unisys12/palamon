<?php

namespace palamon\Requests;

use \ZipArchive;

class Manifest {

     public function checkManifest()
    {

    	$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', $_ENV['BUNGIE_MANIFEST_URL']);
		$res = json_decode($req->getBody(), true);

		$version = $res['Response']['version'];

		return $version;

    }

    public function getManifest()
    {

    	$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', $_ENV['BUNGIE_MANIFEST_URL']);
		$res = json_decode($req->getBody(), true);

		return $res;

    }

    public function downloadMobileWorldContents()
    {

    	// Retrieve Updated Bungie Destiny Manifest
    	$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', $_ENV['BUNGIE_MANIFEST_URL']);
		$res = json_decode($req->getBody(), true);

		// Retrieve path to updated contents path
		$contentpath = $res['Response']['mobileWorldContentPaths']['en'];

		// Perform call on new content path and return the sqlite data
		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', 'http://www.bungie.net' . $contentpath);
		$result = $req->getBody();

		// Create zip archive to store data file
		$zip = new ZipArchive;
		$manifest = $zip->open('manifest' . date('mdY') . '.zip', ZipArchive::CREATE);
		file_put_contents($manifest, $result);

		if ($zip->open($manifest) === TRUE) {
			$zip->extractTo($_SERVER['DOCUMENT_ROOT'].'/cache');
			$zip->close();
			unlink($manifest);
		} else {
			echo "Error putting contents in ZipArchive";
		}

    }

}
