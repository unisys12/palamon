<?php

namespace Palamon\Destiny\Requests;

class Grimoire
{

    private const GRIMOIRE_URL = "https://www.bungie.net/Platform/Destiny/Vanguard/Grimoire/Definition/";

    public function getGrimoire()
    {
        $client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
        $req = $client->get(self::GRIMOIRE_URL, ['verify' => false]);
        $res = json_decode($req->getBody(), true);

        return $res;
    }
}
