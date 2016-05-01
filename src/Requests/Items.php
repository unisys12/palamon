<?php

namespace palamon\Requests;

class Items {

	const ItemsURL = 'http://www.bungie.net/Platform/Destiny/Explorer/Items';
	const ItemURL = 'http://www.bungie.net/platform/Destiny/Manifest/InventoryItem/';

	public function getAllItems($parms){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', isset($params) ? self::ItemsURL . "?" . $params : self::ItemsURL);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

	public function getSingleItem($key){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', self::ItemURL . $key);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

}