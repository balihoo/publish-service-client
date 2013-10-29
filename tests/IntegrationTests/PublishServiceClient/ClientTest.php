<?php
namespace Tests\IntegrationTests\PublishServiceClient;
use PublishServiceClient\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
	/** @var Client */
	protected $client;

	/**
	 * Verify that the only concrete command our client implements successfully gets the service definition
	 * when an API version is supplied
	 * @group integration
	 */
	public function testGetServiceDescriptionExplicitVersion()
	{
		$version = "1.0.1";
		$this->client = new Client($GLOBALS['endpoint'], $GLOBALS['username'], $GLOBALS['password'], $version);

		$serviceDescription = $this->client->getServiceDescription();
		$this->assertEquals($serviceDescription->getApiVersion(), $version);
		$this->assertTrue(count($serviceDescription->getOperations()) > 0);
	}

	/**
	 * Verify that the only concrete command our client implements successfully gets the latest service definition
	 * when an API version is not supplied
	 * @group integration
	 */
	public function testGetServiceDescriptionImplicitLatestVersion()
	{
		$this->client = new Client($GLOBALS['endpoint'], $GLOBALS['username'], $GLOBALS['password']);

		$serviceDescription = $this->client->getServiceDescription();
		$this->assertNotEquals($serviceDescription->getApiVersion(), null);
		$this->assertTrue(count($serviceDescription->getOperations()) > 0);
	}
}
?>