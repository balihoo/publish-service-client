<?php
namespace Tests\UnitTests\PublishServiceClient;
use PublishServiceClient\Client;
use PublishServiceClient\ClientException;
use Guzzle\Http;
use Phockito;
use PhockitoUnit\PhockitoUnitTestCase;

class ClientTest extends PhockitoUnitTestCase
{
	/** @var Http\Client */
	protected $mockHttpClient;

	/** @var Http\Message\Request */
	protected $mockRequest;

	/** @var Http\Message\Response */
	protected $mockResponse;

	public function testClientConstructorSetsEndpoint()
	{
		$endpoint = "http://me.io";
		new Client($endpoint,"user123","secret", $this->mockHttpClient);
		Phockito::verify($this->mockHttpClient, 1)->setBaseUrl($endpoint);
	}

	public function testClientConstructorSetsAuth()
	{
		$username = "user123";
		$password = "secret";
		new Client("http://me.io", $username, $password, $this->mockHttpClient);
		Phockito::verify($this->mockHttpClient, 1)->setDefaultOption('auth', array($username, $password));
	}

	public function testUrlSearchCallsGetWithEncodedUrl()
	{
		$spyClient = Phockito::spy('PublishServiceClient\Client', "http://me.io", "user123", "secret");
		$searchUrl = "http://thisisatest.test/test123";
		Phockito::when($spyClient)->get(anything())->return(array());
		$spyClient->UrlSearch($searchUrl);

		Phockito::verify($spyClient, 1)->get('/search/' . urlencode($searchUrl));
	}

	public function testGetCallsHttpClientGet()
	{
		$path = "/some/route/1";
		Phockito::when($this->mockHttpClient->get($path))->return($this->mockRequest);
		Phockito::when($this->mockRequest->send())->return($this->mockResponse);
		Phockito::when($this->mockResponse->getStatusCode())->return(200);

		$client = new Client("http://me.io", "user123", "secret", $this->mockHttpClient);
		$client->get($path);

		Phockito::verify($this->mockHttpClient, 1)->get($path);
	}

	public function testGetThrowsClientExceptionWhenResponseCodeIs400AndUp()
	{
		$errorMessage = "some error";
		Phockito::when($this->mockResponse->getMessage())->return($errorMessage);
		Phockito::when($this->mockRequest->send())->return($this->mockResponse);
		Phockito::when($this->mockHttpClient->get(anything()))->return($this->mockRequest);

		for($errorCode = 400; $errorCode < 506; $errorCode++)
		{
			Phockito::when($this->mockResponse->getStatusCode()) ->return($errorCode);

			$client = new Client("http://me.io", "user123", "secret", $this->mockHttpClient);

			try{
				$client->get("/some/route/1");
				$this->fail("Expected ClientException");
			} catch(ClientException $ex) {
				$this->assertEquals("Server error occurred. Error code: " . $errorCode . " Response: " . $errorMessage, $ex->getMessage());
				Phockito::reset($this->mockResponse, 'getStatusCode');
			}
		}
	}
}
?>