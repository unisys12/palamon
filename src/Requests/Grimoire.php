<?php
namespace palamon\Requests;

class Grimoire 
{

	const GRIMOIRE_URL = "http://www.bungie.net/Platform/Destiny/Vanguard/Grimoire/Definition/";

    public function __construct($key)
    {
        $this->key = $key;
    }

	public function getGrimoire()
    {
    	$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $this->key]]);
		$req = $client->request('GET', self::GRIMOIRE_URL);
		$res = json_decode($req->getBody(), true);

		return $res;
    }

}
