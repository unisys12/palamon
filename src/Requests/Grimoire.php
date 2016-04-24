<?php

namespace palamon\Requests;

class Grimoire {

	public function getGrimoire()
    {
    	$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', $_ENV['BUNGIE_GRIMOIRE_URL']);
		$res = json_decode($req->getBody(), true);

		return $res;
    }

}