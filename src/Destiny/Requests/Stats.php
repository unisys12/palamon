<?php

namespace Palamon\Destiny\Requests;

class Stats
{

    const STATS_URL = "http://www.bungie.net/Platform/Destiny/Stats/Definition/";

    public function getStats()
    {

        $client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
        $req = $client->request('GET', self::STATS_URL);
        $res = json_decode($req->getBody(), true);

        return $res;
    }
}
