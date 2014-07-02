<?php
namespace PublishServiceClient;
use Guzzle\Common\Collection;
use PublishServiceClient\Exception\MissingConfigurationException;

class Config
{
	/** @var Collection */
	private $guzzleConfig;

	/** @var string */
	private $endpoint;

	/** @var string */
	private $username;

	/** @var string */
	private $password;


	/**
	 * @param array $config
	 * @param string $configSection
	 */
	public function __construct($config, $configSection = null)
	{
		$this->endpoint = $this->getConfigurationItem($config, $configSection, "endpoint");
		$this->username = $this->getConfigurationItem($config, $configSection, "username");
		$this->password = $this->getConfigurationItem($config, $configSection, "password");

		$this->guzzleConfig = Collection::fromConfig(array(
			"base_url" => $this->endpoint,
			"username" => $this->username,
			"password" => $this->password));
	}

	/**
	 * @param array $config
	 * @param string $configSection
	 * @param string $configItem
	 * @param mixed $defaultValue
	 * @return mixed
	 * @throws MissingConfigurationException
	 */
	private function getConfigurationItem($config, $configSection, $configItem, $defaultValue = null)
	{
		if ($configSection == null)
		{
			if (isset($config[$configItem]))
			{
				return ($config[$configItem]);
			}
			else
			{
				if ($defaultValue)
				{
					return $defaultValue;
				}
				throw new MissingConfigurationException($configItem);
			}
		}
		else
		{
			if (isset($config[$configSection][$configItem]))
			{
				return ($config[$configSection][$configItem]);
			}
			else
			{
				if ($defaultValue)
				{
					return $defaultValue;
				}
				throw new MissingConfigurationException($configSection . "/" . $configItem);
			}
		}
	}

	public function getGuzzleConfig()
	{
		return $this->guzzleConfig;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function getEndpoint()
	{
		return $this->endpoint;
	}

}