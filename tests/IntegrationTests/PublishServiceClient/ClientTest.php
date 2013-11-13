<?php
namespace Tests\IntegrationTests\PublishServiceClient;
use PublishServiceClient\Client;
use PublishServiceClient\Config;

class ClientTest extends \PHPUnit_Framework_TestCase
{
	/** @var Client */
	protected $client;

	/**
	 * Gets the service definition when an API version is supplied
	 * @group integration
	 */
	public function testGetServiceDescriptionExplicitVersion()
	{
		$version = "1.0.1";
		$config = new Config(array(
			'endpoint' => $GLOBALS['endpoint'],
			'username' => $GLOBALS['adminUsername'],
			'password' => $GLOBALS['adminPassword'],
			'version' => $version));
		$this->client = new Client($config);

		$serviceDescription = $this->client->getServiceDescription();
		$this->assertEquals($serviceDescription->getApiVersion(), $version);
		$this->assertTrue(count($serviceDescription->getOperations()) > 0);
	}

	/**
	 * Gets the latest service definition when an API version is not supplied
	 * @group integration
	 */
	public function testGetServiceDescriptionImplicitLatestVersion()
	{
		$config = new Config(array(
			'endpoint' => $GLOBALS['endpoint'],
			'username' => $GLOBALS['adminUsername'],
			'password' => $GLOBALS['adminPassword']));

		$this->client = new Client($config);
		$serviceDescription = $this->client->getServiceDescription();
		$this->assertNotEquals($serviceDescription->getApiVersion(), null);
		$this->assertTrue(count($serviceDescription->getOperations()) > 0);
	}

	/**
	 * Gets the service definition when an API version is supplied
	 * @group integration
	 */
	public function testPing()
	{
		$version = "1.0.1";
		$config = new Config(array(
			'endpoint' => $GLOBALS['endpoint'],
			'username' => $GLOBALS['adminUsername'],
			'password' => $GLOBALS['adminPassword'],
			'version' => $version));
		$this->client = new Client($config);

		$this->assertTrue($this->client->ping());
	}
}
?>