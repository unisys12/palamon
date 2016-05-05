<?php
namespace palamon\Requests;

class TalentNodes
{

	const TALENT_NODES = 'http://www.bungie.net/Platform/Destiny/Explorer/TalentNodeSteps';

	public function getTalentNodes($params)
    {

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);

		$req = $client->request('GET', isset($params) ? self::TALENT_NODES . "?" . $params : self::TALENT_NODES);

		$res = json_decode($req->getBody(), true);

		return $res;

	}	

}
