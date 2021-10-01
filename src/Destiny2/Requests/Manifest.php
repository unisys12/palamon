<?php

namespace Palamon\Destiny2\Requests;

use Error;
use ZipArchive;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Message;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

/**
 * Handles the Destiny Manifest File
 *
 * @author unisys12 <unisys12@gmail.com>
 */

class Manifest
{
    protected const MANIFEST_URL = "Destiny2/Manifest/";
    protected const BASE_URI = "https://www.bungie.net/Platform/";

    /**
     * Requires use of a API Key obtained from Bungie.net
     *
     * @param string $lang The language you wish to fetch
     */
    public function __construct(protected string $lang)
    {
        $this->lang = $lang;
    }

    /**
     * Fetches the Destiny 2 Manifest
     *
     * @return string The response body of the manifest request
     */
    public function getManifest()
    {

        $client = new Client(
            [
                'base_uri' => self::BASE_URI,
                'headers' =>
                ['X-API-KEY' => getenv("BUNGIE_KEY")],
            ]
        );

        try {
            $req = $client->get(
                self::MANIFEST_URL,
                ['verify' => false]
            );
            return json_decode($req->getBody(), true);
        } catch (ClientException $ce) {
            return Message::toString($ce->getResponse());
        }
    }

    /**
     * Gets the current JSONContentPath
     * VERY BIG DOWNLOAD!!
     *
     * @return string
     */
    public function getJsonWorldContentPaths()
    {
        $manifest = $this->getManifest();
        $path = !empty($manifest['Response']['jsonWorldContentPaths'][$this->lang])
            ? $manifest['Response']['jsonWorldContentPaths'][$this->lang]
            : throw new Error("There was an error fetching the json content path your requested.");
        return $path;
    }

    // Don't say I didn't warn you of the large download! >30MB of JSON
    public function getAllJson()
    {
        $contentPath = $this->getJsonWorldContentPaths();

        // NOTE: Extract our into seperate Service maybe?
        $client = new Client(
            [
                'base_uri' => self::BASE_URI,
                'headers' => ['X-API-KEY' => getenv('BUNGIE_KEY')],
                'timeout' => 125
            ]
        );

        try {
            $req = $client->get(
                $contentPath,
                ['verify' => false, 'progress' => function (
                    $downloadTotal,
                    $downloadedBytes
                ) {
                    // I can do something with this info!
                    // echo '<p>Download Total: ' . $downloadTotal . '</p>';
                    // echo '<p>Download Bytes: ' . $downloadedBytes . '</p>';
                }]
            );
            return json_decode($req->getBody());
        } catch (ClientException $ce) {
            return Message::toString($ce->getResponse());
        }
    }

    /**
     * Gets the path to a single table in JSON format
     * Suited for most needs. Download only what you need
     *
     * @param string $table
     *
     * @return bool
     */
    public function getJsonTablePath(string $table)
    {
        $manifest = $this->getManifest();

        $ComponentContentPath = !empty($manifest['Response']["jsonWorldComponentContentPaths"][$this->lang][$table])
            ? $manifest['Response']["jsonWorldComponentContentPaths"][$this->lang][$table]
            : print throw new Error("There was an issue fetching the JSON data for the table " . $table);

        return $ComponentContentPath;
    }

    /**
     * Returns JSON for a given jsonWorldComponentContentPath
     *
     * @param string $tablename
     * @return string
     */
    public function getJsonContent(string $tablename)
    {
        $path = $this->getJsonTablePath($tablename);

        $client = new Client(
            [
                'base_uri' => self::BASE_URI,
                'headers' => ['X-API-KEY' => getenv('BUNGIE_KEY')],
                'timeout' => 125
            ]
        );

        try {
            $req = $client->get($path, ['verify' => false]);
            return json_decode($req->getBody());
        } catch (ClientException $ce) {
            return Message::toString($ce->getResponse());
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
        $version = !empty($manifest['Response']['version'])
            ? $manifest['Response']['version']
            : throw new Error('There was an issue retrieveing the version of the current manifest.');
        return $version;
    }

    /**
     * Get the current SQLite3 Content Path
     *
     * @return string
     */
    private function getSQLiteContentPath()
    {
        $manifest = $this->getManifest();
        $mobile_path = !empty($manifest['Response']['mobileWorldContentPaths'][$this->lang])
            ? $manifest['Response']['mobileWorldContentPaths'][$this->lang]
            : throw new Error("There was an error fetching the MobileWorldContent path you requested.");
        return $mobile_path;
    }

    /**
     * Download MobileWorldContentFile (SQLite)
     *
     * @throws ClientException
     * @return string
     */
    private function downloadSql()
    {
        $contentPath = $this->getSQLiteContentPath();
        // $contentPath =
        //"/common/destiny2_content/sqlite/en/world_sql_content_336e2859ed44cbe84441ca18a05a2f35.content";

        // NOTE: Extract our into seperate Service maybe?
        $client = new Client(
            [
                'base_uri' => self::BASE_URI,
                'headers' => ['X-API-KEY' => getenv('BUNGIE_KEY')],
                'timeout' => 125
            ]
        );

        try {
            $req = $client->get(
                $contentPath,
                [
                    'verify' => false,
                    // 'progress' => function (
                    //     $downloadTotal,
                    //     $downloadedBytes
                    // ) {
                    //     // I can do something with this info!
                    //     echo '<p>Download Total: ' . $downloadTotal . '</p>';
                    //     echo '<p>Download Bytes: ' . $downloadedBytes . '</p>';
                    // }
                ]
            );
            return $req->getBody();
        } catch (ClientException $ce) {
            return Message::toString($ce->getResponse());
        }
    }

    /**
     * Extract contents of compressed database
     *
     * @param string $path Path to storage location of extract sqlite db
     *
     * @return bool
     */
    private function extractZip(string $path)
    {
        $zip = new ZipArchive();
        $zip->open($path);
        $zip->extractTo('./storage/destiny2/');

        return $zip->close();
    }

    /**
     * Extract the contents of the zipped manifest
     *
     * @return bool
     */
    public function downloadMobileWorldContents()
    {
        $sql = $this->downloadSql();
        $file = file_put_contents('./destiny2.zip', $sql);

        $zip_contents = $this->extractZip('./destiny2.zip');

        return $zip_contents;
    }
}
