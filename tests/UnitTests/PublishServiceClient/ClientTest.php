<?php
namespace Tests\UnitTests\PublishServiceClient;
use PublishServiceClient;
use Guzzle\Http;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use Phockito;
use PhockitoUnit\PhockitoUnitTestCase;

class ClientTest extends PhockitoUnitTestCase
{
	/** @var Http\Message\Request */
	protected $mockRequest;

	/** @var Http\Message\Response */
	protected $mockResponse;

	/** @var PublishServiceClient\Client */
	protected $client;

	/** @var String */
	protected $version = "1.0.1";

	public function setUp()
	{
		parent::setUp();
		$this->client = Phockito::spy('PublishServiceClient\Client', "http://me.io", "user", "pass", $this->version);
	}

	/**
	 * If the server returns an error 400 or above getServiceDescription throws a PublishServerException
	 * @group unit
	 */
	public function testGetServiceDescriptionThrowsClientException()
	{
		$errorMessage = "some error";
		Phockito::when($this->mockResponse->getMessage())->return($errorMessage);
		Phockito::when($this->mockRequest->send())->return($this->mockResponse);
		Phockito::when($this->client)->get(anything())->return($this->mockRequest);

		for($errorCode = 400; $errorCode < 506; $errorCode++)
		{
			Phockito::when($this->mockResponse->getStatusCode())->return($errorCode);

			try
			{
				$this->client->getServiceDescription();
				$this->fail("Expected PublishServerException");
			} catch(PublishServiceClient\PublishServerException $ex) {
				$this->assertEquals("Server error occurred. Error code: " . $errorCode . " Response: " . $errorMessage, $ex->getMessage());
				Phockito::reset($this->mockResponse, 'getStatusCode');
			}
		}
	}

	/**
	 * If the server returns an empty body getServiceDescription throws an InvalidApiVersionException
	 * @group unit
	 */
	public function testGetServiceDescriptionThrowsInvalidApiVersionException()
	{
		Phockito::when($this->mockResponse->getStatusCode())->return(200);
		Phockito::when($this->mockResponse->json())->return(array());
		Phockito::when($this->mockRequest->send())->return($this->mockResponse);
		Phockito::when($this->client)->get(anything())->return($this->mockRequest);

		try
		{
			$this->client->getServiceDescription();
			$this->fail("Expected InvalidApiVersionException");
		}
		catch(PublishServiceClient\InvalidApiVersionException $ex) {
			$this->assertEquals("The service does not have a service description for the requested API version: " . $this->version, $ex->getMessage());
		}
	}

	/**
	 * getServiceDescription returns a ServiceDescription
	 * @group unit
	 */
	public function testGetServiceDescriptionReturnsServiceDescription()
	{
		$testDescription = array(
			"name" => "test app",
			"apiVersion" => "1.0.1",
			"description" => "this is a test",
			"operations" => array(
				"TestOperation" => array(
					"httpMethod" => "PUT",
					"uri" => "/brand/{BrandKey}",
					"summary" => "Create a brand",
					"parameters" => array(
						"BrandKey" => array(
							"location" => "uri",
							"required" => true
						)
					)
				)
			)
		);

		Phockito::when($this->mockResponse->getStatusCode())->return(200);
		Phockito::when($this->mockResponse->json())->return($testDescription);
		Phockito::when($this->mockRequest->send())->return($this->mockResponse);
		Phockito::when($this->client)->get(anything())->return($this->mockRequest);

		$serviceDescription = $this->client->getServiceDescription();
		$this->assertEquals(ServiceDescription::factory($testDescription), $serviceDescription);
	}

	/**
	 * Executing other operations sets the service description
	 * @group unit
	 */
	public function testOtherOperationsSetServiceDescription()
	{
		$testDescription = array(
			"name" => "test app",
			"apiVersion" => "1.0.1",
			"description" => "this is a test",
			"operations" => array(
				"TestOperation" => array(
					"httpMethod" => "PUT",
					"uri" => "/brand/{BrandKey}",
					"summary" => "Create a brand",
					"parameters" => array(
						"BrandKey" => array(
							"location" => "uri",
							"required" => true
						)
					)
				)
			)
		);

		Phockito::when($this->mockResponse->getStatusCode())->return(200);
		Phockito::when($this->mockResponse->json())->return($testDescription);
		Phockito::when($this->mockRequest->send())->return($this->mockResponse);
		Phockito::when($this->client)->get(anything())->return($this->mockRequest);
		Phockito::when($this->client)->callParent(anything(), anything())->return(null);
		$this->assertEquals(null, $this->client->getDescription());
		$this->client->SomeOperation(array());
		$this->assertEquals(ServiceDescription::factory($testDescription), $this->client->getDescription());
	}
}
?>