<?php

namespace palamon\Requests;

class Items {

	public function getAllItems(){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', $_ENV['BUNGIE_ITEMS_URL']);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

	public function getAllItems($parms){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', $_ENV['BUNGIE_ITEMS_URL'] . '?' . $parms);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

	public function getSingleItem($key){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', 'http://www.bungie.net/platform/Destiny/Manifest/InventoryItem/' . $key);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

}