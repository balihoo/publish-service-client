<?php
namespace Balihoo\PublishServiceClient;

/**
 * Class Client
 * @package Balihoo\PublishServiceClient
 *
 * The root object for the client library
 */
class Client
{
	// @var string
	private $endpoint;
	// @var string
	private $username;
	// @var string
	private $password;

	public function __construct($endpoint, $username, $password)
	{
		$this->endpoint = $endpoint;
		$this->username = $username;
		$this->password = $password;
	}

	/**
	 * @param $url
	 * @return \stdClass
	 */
	public function UrlSearch($url)
	{

		return new \stdClass();
	}
}
?>