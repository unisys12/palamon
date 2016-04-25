<?php

namespace palamon\Requests;

class TalentNodes {

	public function getTalentNodes(){

		$client = new \GuzzleHttp\Client(['headers' => ['X-API-KEY' => $_ENV['BUNGIE_KEY']]]);
		$req = $client->request('GET', $_ENV['BUNGIE_TALENTNODES_URL']);
		$res = json_decode($req->getBody(), true);

		return $res;

	}

}