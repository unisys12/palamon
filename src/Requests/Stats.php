<?php

namespace palamon\Requests;

class Stats {

	public function getStats(){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', $_ENV['BUNGIE_STATS_URL']);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

}