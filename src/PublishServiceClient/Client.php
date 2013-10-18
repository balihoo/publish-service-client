<?php
namespace PublishServiceClient;
use \Guzzle\Http;

/**
 * Class Client
 * @package PublishServiceClient
 *
 * The root object for the client library
 */
class Client
{
	/** @var Http\Client */
	private $httpClient;

	/**
	 * @param string $endpoint
	 * @param string $username
	 * @param string $password
	 * @param Http\Client $httpClient
	 */
	public function __construct($endpoint, $username, $password, Http\Client $httpClient = null)
	{
		$this->endpoint = $endpoint;
		$this->username = $username;
		$this->password = $password;

		if (!$httpClient)
		{
			$httpClient = new Http\Client();
		}
		$httpClient->setBaseUrl($endpoint);
		$httpClient->setDefaultOption('auth', array($username, $password));
		$this->httpClient = $httpClient;
	}

	/**
	 * @param $url
	 * @return \stdClass
	 */
	public function UrlSearch($url)
	{
		return $this->get('/search/' . urlencode($url));
	}

	/**
	 * @param string $url
	 * @return Http\Message\Response
	 * @throws \ClientException
	 */
	private function get($url)
	{
		$request = $this->httpClient->get($url);
		$response = $request->send();

		if ($response->getStatusCode() > 400)
		{
			throw new \ClientException($response);
		}

		return $response->json();
	}
}
?>