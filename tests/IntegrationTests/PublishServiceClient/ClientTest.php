<?php
namespace Tests\IntegrationTests\PublishServiceClient;
use PublishServiceClient\Client;
use PublishServiceClient\Config;

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
	 * Verify that the only concrete command our client implements successfully gets the latest service definition
	 * when an API version is not supplied
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
	 * Verify that one of the dynamically generated commands works
	 * @group integration
	 */
	public function testCreateTemplateVersion()
	{
		$config = new Config(array(
			'endpoint' => $GLOBALS['endpoint'],
			'username' => $GLOBALS['username'],
			'password' => $GLOBALS['password']));

		$this->client = new Client($config);

		$filename = "/home/jflitton/test.zip";
		$filePointer = fopen($filename, 'r');
		$templateCode = fread($filePointer, filesize($filename));
        $templateCode = base64_encode($templateCode);

		$templateVersion = $this->client->createTemplateversion(array("BrandKey" => "testbrand1", "TemplateID" => 1, "TemplateCode" => $templateCode, "Publish" => true));
		$this->assertTrue(isset($templateVersion['TemplateVersionID']));
		$this->assertNotEquals($templateVersion['TemplateVersionID'], null);
	}
}
?>