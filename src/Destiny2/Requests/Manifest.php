<?php

namespace Palamon\Destiny2\Requests;

use ZipArchive;
use GuzzleHttp\Psr7;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

/**
 * Handles the Destiny Manifest File
 * 
 * @author unisys12 <unisys12@gmail.com>
 */

class Manifest
{
    const MANIFEST_URL = "Manifest";

    /**
     * Requires use of a API Key obtained from Bungie.net
     *
     * @param integer $key  Your Bungie API Key from ENV
     * @param string  $lang The language you wish to fetch
     */
    public function __construct($lang) 
    {
        $this->lang = $lang;
    }

    /**
     * Fetches the Destiny 2 Manifest
     *
     * @return resource The response body of the manifest request
     */
    public function getManifest()
    {

        $client = new Client(
            [
                'base_uri' => 'https://www.bungie.net/Platform/Destiny2/',
                'headers' => ['X-API-KEY' => getenv('BUNGIE_KEY')],
            ]
        );

        try {
            $req = $client->get(
                self::MANIFEST_URL,
                ['verify' => false]
            );
            return json_decode($req->getBody(), true);
        } catch (ClientException $ce) {
            if ($ce->hasResponse()) {
                return Psr7\str($ce->getResponse());
            }
        }

    }

    /**
     * Gets the current version of the fetched Manifest
     *
     * @return integer|null The version of the Manfiest
     */
    public function getVersion()
    {
        $manifest = self::getManifest();
        return $manifest['Response']['version'];
    }

    /**
     * Gets the current JSONContentPath
     * 
     * @return string
     */
    private function _getJsonWorldContentPaths()
    {
        $manifest = self::getManifest();
        return $manifest['Response']['jsonWorldContentPaths'][$this->lang];
    }

    /**
     * Download JSONWorldContent
     * 
     * @return resource
     */
    // public function downloadJSON()
    // {
    //     $contentPath = self::_getJsonWorldContentPaths();

    //     // NOTE: Extract our into seperate Service maybe?
    //     $client = new Client(
    //         [
    //             'base_uri' => 'https://www.bungie.net/Platform/',
    //             'headers' => ['X-API-KEY' => $this->key],
    //             'timeout' => 125
    //         ]
    //     );

    //     try {
    //         $req = $client->get(
    //             $contentPath, ['verify' => false, 'progress' => function (
    //                 $downloadTotal,
    //                 $downloadedBytes
    //             ) {
    //                 // I can do something with this info!
    //                 // echo '<p>Download Total: ' . $downloadTotal . '</p>';
    //                 // echo '<p>Download Bytes: ' . $downloadedBytes . '</p>';
    //             }]
    //         );
    //         return json_decode($req->getBody());
    //     } catch (ClientException $ce) {
    //         if ($ce->hasResponse()) {
    //             return Psr7\str($ce->getResponse());
    //         }
    //     }
        
    // }

    /**
     * Get the current SQLite3 Content Path
     * 
     * @return string
     */
    private function _getSQLiteContentPath()
    {
        $manifest = self::getManifest();
        return $manifest['Response']['mobileWorldContentPaths'][$this->lang];
    }

    /**
     * Download MobileWorldContentFile (SQLite)
     *
     * @return resource
     */
    private function _downloadSql()
    {        
        $contentPath = self::_getSQLiteContentPath();

        // NOTE: Extract our into seperate Service maybe?
        $client = new Client(
            [
                'base_uri' => 'https://www.bungie.net/Platform/',
                'headers' => ['X-API-KEY' => $this->key],
                'timeout' => 125
            ]
        );

        try {
            $req = $client->get(
                $contentPath, ['verify' => false, 'progress' => function (
                    $downloadTotal,
                    $downloadedBytes
                ) {
                    // I can do something with this info!
                    // echo '<p>Download Total: ' . $downloadTotal . '</p>';
                    // echo '<p>Download Bytes: ' . $downloadedBytes . '</p>';
                }]
            );
            return $req->getBody();
        } catch (ClientException $ce) {
            if ($ce->hasResponse()) {
                return Psr7\str($ce->getResponse());
            }
        }
    }

    /**
     * Extract contents of compressed database
     */
    private function _extractZip($path)
    {
        echo "Extracting file from " . $path;
        $zip = new ZipArchive;
        $zip->open($path);
        $zip->extractTo('../database/');
        $zip->close();
    }

    /**
     * Extract the contents of the zipped manifest
     *
     * @return resource
     */
    public function extractSqlite()
    {
        $sql = self::_downloadSql();
        $file = file_put_contents('./destiny2.zip', $sql);

        $zip_contents = self::_extractZip('./destiny2.zip');

        return $zip_contents;      
    }
}