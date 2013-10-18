<?php
namespace Tests\UnitTests\PublishServiceClient;
use PublishServiceClient\Client;
use Guzzle\Http;
use Phockito;
use PhockitoUnit\PhockitoUnitTestCase;

class ClientTest extends PhockitoUnitTestCase
{
	/** @var Http\Client */
	protected $mockHttpClient;

	public function testClientConstructorSetsEndpoint()
	{
		$endpoint = "https://thisisatest.com/test";
		new Client($endpoint,"user","password", $this->mockHttpClient);
		Phockito::verify($this->mockHttpClient, 1)->setBaseUrl($endpoint);
	}
}
?>