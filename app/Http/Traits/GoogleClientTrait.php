<?php

namespace App\Http\Traits;

use Google_Client;

/**
 * Class GoogleClientTrait
 *
 * @package \App\Http\Traits
 */
trait GoogleClientTrait {

	/**
	 * Returns an authorized API client.
	 * @return Google_Client the authorized client object
	 */
	function initClient()
	{
		$client = new Google_Client();
		$client->setApplicationName(env('APP_NAME'));
		$client->setDeveloperKey(env('GOOGLE_SERVER_KEY'));
		$client->setClientId(env('GOOGLE_CLIENT_ID'));
		$client->setClientSecret(env('GOOGLE_APP_SECRET'));

		return $client;

	}

	/**
	 * Returns an authorized API client.
	 *
	 * @param $client
	 * @return Google_Client the authorized client object
	 */
	function getClient($client)
	{
		$tokens = $this->getUserTokens();
		$client->setAccessToken($tokens);
		return $this->refreshTokens($client, $tokens['refresh_token']);
	}

	/**
	 * Get User google tokens.
	 *
	 * @return array
	 */
	function getUserTokens()
	{
		$userTokens = auth()->user()->tokens->first();

		// Set token for the Google API PHP Client
		$google_client_token = [
			'access_token' => $userTokens->access_token,
			'refresh_token' => $userTokens->refresh_token,
			'expires_in' => $userTokens->expires_in
		];

		return $google_client_token;
	}

	/**
	 * Refresh User tokens.
	 *
	 * @param $client
	 * @return Google_Client
	 */
	function refreshTokens($client)
	{
		if ($client->isAccessTokenExpired()) {
			$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
			$newTokens = $this->client->getAccessToken();
			auth()->user()->tokens->first()->update($newTokens);
			$client->setAccessToken($newTokens);
			return $client;
		} else {
			return $client;
		}
	}
}
