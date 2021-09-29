<?php

namespace Palamon\Destiny2\Requests;

use ZipArchive;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

/**
 * Handles the Destiny Manifest File
 *
 * @author unisys12 <unisys12@gmail.com>
 */

class Manifest
{
    protected const MANIFEST_URL = "Manifest";

    /**
     * Requires use of a API Key obtained from Bungie.net
     *
     * @param string $lang The language you wish to fetch
     */
    public function __construct(string $lang)
    {
        $this->lang = $lang;
    }

    /**
     * Fetches the Destiny 2 Manifest
     *
     * @return array The response body of the manifest request
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
                return Message::toString($ce->getResponse());
            }
        }
    }

    /**
     * Gets the current version of the fetched Manifest
     *
     * @return string The version of the Manfiest
     */
    public function getVersion()
    {
        $manifest = $this->getManifest();
        return $manifest['Response']['version'];
    }

    /**
     * Gets the current JSONContentPath
     *
     * @return string
     */
    public function getJsonWorldContentPaths()
    {
        $manifest = $this->getManifest();
        return $manifest['Response']['jsonWorldContentPaths'][$this->lang];
    }

    /**
     * Download JSONWorldContent
     *
     * @return resource
     */
    // public function downloadJSON()
    // {
    //     $contentPath = $this->getJsonWorldContentPaths();

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
    private function getSQLiteContentPath()
    {
        $manifest = $this->getManifest();
        return $manifest['Response']['mobileWorldContentPaths'][$this->lang];
    }

    /**
     * Download MobileWorldContentFile (SQLite)
     *
     * @return resource
     */
    private function downloadSql()
    {
        $contentPath = $this->getSQLiteContentPath();
        // $contentPath =
        //"/common/destiny2_content/sqlite/en/world_sql_content_336e2859ed44cbe84441ca18a05a2f35.content";

        // NOTE: Extract our into seperate Service maybe?
        $client = new Client(
            [
                'base_uri' => 'https://www.bungie.net/Platform/',
                'headers' => ['X-API-KEY' => getenv('BUNGIE_KEY')],
                'timeout' => 125
            ]
        );

        try {
            $req = $client->get(
                $contentPath,
                [
                    'verify' => false,
                    'progress' => function (
                        $downloadTotal,
                        $downloadedBytes
                    ) {
                        // I can do something with this info!
                        // echo '<p>Download Total: ' . $downloadTotal . '</p>';
                        // echo '<p>Download Bytes: ' . $downloadedBytes . '</p>';
                    }
                ]
            );
            return $req->getBody();
        } catch (ClientException $ce) {
            if ($ce->hasResponse()) {
                return Message::toString($ce->getResponse());
            }
        }
    }

    /**
     * Extract contents of compressed database
     *
     * @param string $path Path to storage location of extract sqlite db
     *
     * @return void
     */
    private function extractZip(string $path)
    {
        $zip = new ZipArchive();
        $zip->open($path);
        $zip->extractTo('./storage/destiny2/');
        $zip->close();
    }

    /**
     * Extract the contents of the zipped manifest
     *
     * @return resource
     */
    public function downloadMobileWorldContents()
    {
        $sql = $this->downloadSql();
        $file = file_put_contents('./destiny2.zip', $sql);

        $zip_contents = $this->extractZip('./destiny2.zip');

        return $zip_contents;
    }
}
