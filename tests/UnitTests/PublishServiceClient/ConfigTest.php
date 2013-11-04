<?php
namespace Tests\UnitTests\PublishServiceClient;
use PublishServiceClient;
use PublishServiceClient\Config;
use Guzzle\Http;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use Phockito;
use PhockitoUnit\PhockitoUnitTestCase;

class ConfigTest extends PhockitoUnitTestCase
{
	/**
	 * Verify that the constructor requires an endpoint
	 * @group unit
	 */
	public function testConstructorRequiresEndpoint()
	{
		try
		{
			new Config(array('username'=>'user', 'password'=>'pass', 'version'=>'1.0.1'));
			$this->fail("Expected MissingConfigurationException");
		} catch(PublishServiceClient\Exception\MissingConfigurationException $ex) {
			$this->assertEquals("Required configuration item endpoint missing", $ex->getMessage());
		}
	}

	/**
	 * Verify that the constructor requires a username
	 * @group unit
	 */
	public function testConstructorRequiresUsername()
	{
		try
		{
			new Config(array('endpoint'=> 'http://test.endpoint', 'password'=>'pass', 'version'=>'1.0.1'));
			$this->fail("Expected MissingConfigurationException");
		} catch(PublishServiceClient\Exception\MissingConfigurationException $ex) {
			$this->assertEquals("Required configuration item username missing", $ex->getMessage());
		}
	}

	/**
	 * Verify that the constructor requires a password
	 * @group unit
	 */
	public function testConstructorRequiresPassword()
	{
		try
		{
			new Config(array('endpoint'=> 'http://test.endpoint', 'username'=>'user', 'version'=>'1.0.1'));
			$this->fail("Expected MissingConfigurationException");
		} catch(PublishServiceClient\Exception\MissingConfigurationException $ex) {
			$this->assertEquals("Required configuration item password missing", $ex->getMessage());
		}
	}

	/**
	 * Verify that the optional config section pulls values from the specified section
	 * @group init
	 */
	public function testConfigSection()
	{
		$endpoint = 'http://test.endpoint';
		$username = 'user';
		$password = 'pass';
		$version = '1.0.1';
		$configSection = 'publishClient';

		$config = new Config(array(
			'endpoint' => 'bad'.$endpoint,
			'username' => 'bad'.$username,
			'password' => 'bad'.$password,
			'version' => 'bad'.$version,
			$configSection => array(
				'endpoint' => $endpoint,
				'username' => $username,
				'password' => $password,
				'version' => $version)), $configSection);

		$this->assertEquals($config->getEndpoint(), $endpoint);
		$this->assertEquals($config->getUsername(), $username);
		$this->assertEquals($config->getPassword(), $password);
		$this->assertEquals($config->getVersion(), $version);
	}

	/**
	 * Verify that the required parameters are still enforced when specifying a config section
	 * @group unit
	 */
	public function testRequiredParametersWithConfigSection()
	{
		try
		{
			new Config(array('section'=>array(
				'endpoint'=> 'http://test.endpoint',
				'username'=>'user',
				'version'=>'1.0.1')), 'section');
			$this->fail("Expected MissingConfigurationException");
		} catch(PublishServiceClient\Exception\MissingConfigurationException $ex) {
			$this->assertEquals("Required configuration item section/password missing", $ex->getMessage());
		}
	}

	/**
	 * Verify that the constructor and associated get methods maintain the integrity of the supplied data
	 * @group unit
	 */
	public function testConfigurationData()
	{
		$endpoint = 'http://test.endpoint';
		$username = 'user';
		$password = 'pass';
		$version = '1.0.1';

		$config = new Config(array(
			'endpoint' => $endpoint,
			'username' => $username,
			'password' => $password,
			'version' => $version));

		$this->assertEquals($config->getEndpoint(), $endpoint);
		$this->assertEquals($config->getUsername(), $username);
		$this->assertEquals($config->getPassword(), $password);
		$this->assertEquals($config->getVersion(), $version);
	}

	/**
	 * Verify that the version parameter defaults to "latest"
	 * @group unit
	 */
	public function testDefaultVersion()
	{
		$endpoint = 'http://test.endpoint';
		$username = 'user';
		$password = 'pass';

		$config = new Config(array(
			'endpoint' => $endpoint,
			'username' => $username,
			'password' => $password
		));

		$this->assertEquals($config->getEndpoint(), $endpoint);
		$this->assertEquals($config->getUsername(), $username);
		$this->assertEquals($config->getPassword(), $password);
		$this->assertEquals($config->getVersion(), "latest");
	}

	/**
	 * Verify that the version parameter defaults to "latest" when specifying a config section
	 * @group unit
	 */
	public function testDefaultVersionConfigSection()
	{
		$endpoint = 'http://test.endpoint';
		$username = 'user';
		$password = 'pass';

		$config = new Config(array('section' => array(
			'endpoint' => $endpoint,
			'username' => $username,
			'password' => $password
		)), 'section');

		$this->assertEquals($config->getEndpoint(), $endpoint);
		$this->assertEquals($config->getUsername(), $username);
		$this->assertEquals($config->getPassword(), $password);
		$this->assertEquals($config->getVersion(), "latest");
	}
}
?>