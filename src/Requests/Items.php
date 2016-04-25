<?php

namespace palamon\Requests;

class Items {

	public function getItems(){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', $_ENV['BUNGIE_ITEMS_URL']);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

}