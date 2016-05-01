<?php

namespace palamon\Requests;

class TalentNodes {

	const TalentNodes = 'http://www.bungie.net/Platform/Destiny/Explorer/TalentNodeSteps';

	public function getTalentNodes($params){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);

		$req = $client->request('GET', isset($params) ? self::TalentNodes . "?" . $params : self::TalentNodes);

		$res = json_decode($req->getBody(), true);

		return $res;

	}	

}