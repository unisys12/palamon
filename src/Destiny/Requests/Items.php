<?php
namespace palamon\Destiny\Requests;

class Items 
{

	const ITEMS_URL = 'http://www.bungie.net/Platform/Destiny/Explorer/Items';
	const ITEM_URL = 'http://www.bungie.net/platform/Destiny/Manifest/InventoryItem/';

	public function getAllItems($parms)
	{

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', isset($params) ? self::ITEMS_URL . "?" . $params : self::ITEMS_URL);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

	public function getSingleItem($key)
	{

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', self::ITEM_URL . $key);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

}
