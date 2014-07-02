<?php
namespace PublishServiceClient;
use Guzzle\Service;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;
use PublishServiceClient\Exception\PublishServerException;
use PublishServiceClient\Exception\InvalidApiVersionException;

/**
 * Class PublishServiceClient
 *
 * A Guzzle service client for connecting to the Microsite Publish Service
 */
class Client extends Service\Client
{
	/** @var String $version */

	public function __construct(Config $config)
	{
		parent::__construct($config->getEndpoint(), $config->getGuzzleConfig());

		// Set up basic auth
		$this->setDefaultOption('auth', array($config->getUsername(), $config->getPassword()));
	}

	/**
	 * Gets the service description from the publish service.
	 *
	 * @return ServiceDescription
	 */

	public function getFileContents()
	{
		$fileContents = file_get_contents(dirname(__FILE__) . "/guzzle.json", "r");
		//$description = json_decode($fileContents, true);
		return $fileContents;
	}


	public function getServiceDescription()
	{
		$description = json_decode($this->getFileContents(), true);

		if ($description && isset($description['apiVersion']))
		{
			return ServiceDescription::factory($description);
		}
		return null;
	}

	/**
	 * Pings the publish service.
	 *
	 * @return ServiceDescription
	 * @throws InvalidApiVersionException
	 * @throws PublishServerException
	 */
	public function ping()
	{
		$request = $this->get('/ping');
		$response = $request->send();

		if ($response->getStatusCode() >= 400)
		{
			throw new PublishServerException($response);
		}

		$pingResponse = $response->json();
		return ($pingResponse && isset($pingResponse['status']) && $pingResponse['status'] == 'success');
	}

	/**
	 * If service description has not been applied, get it from the server and apply it,
	 * then call the parent magic method to execute the supplied command.
	 *
	 * @param string $method Name of the command object to instantiate
	 * @param array  $args   Arguments to pass to the command
	 *
	 * @return mixed Returns the result of the command
	 */
	public function __call($method, $args)
	{
		if (!$this->serviceDescription)
		{
			// Attach a service description to the client, dynamically building all the commands for the client
			$serviceDescription = $this->getServiceDescription();
			$this->setDescription($serviceDescription);
		}
		return $this->callParent($method, $args);
	}

	/**
	 * Exists solely to allow unit testing of the __call function above
	 *
	 * @param string $method
	 * @param array $args
	 * @return mixed
	 */
	public function callParent($method, $args)
	{
		return parent::__call($method, $args);
	}
}
?>