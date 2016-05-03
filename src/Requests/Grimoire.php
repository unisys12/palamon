<?php

namespace palamon\Requests;

class Grimoire {

	const GrimoreURL = "http://www.bungie.net/Platform/Destiny/Vanguard/Grimoire/Definition/";

	public function getGrimoire()
    {
    	$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', self::GrimoreURL);
		$res = json_decode($req->getBody(), true);

		return $res;
    }

}