<?php

namespace palamon\Requests;

class Stats {

	const StatsURL = "http://www.bungie.net/Platform/Destiny/Stats/Definition/";

	public function getStats(){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', self::StatsURL);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

}