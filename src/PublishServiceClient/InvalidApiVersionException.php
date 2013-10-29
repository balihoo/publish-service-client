<?php
namespace PublishServiceClient;
use \Guzzle\Http\Message\Response;

class InvalidApiVersionException extends \Exception
{
	/**
	 * @param string $version
	 */
	public function __construct($version)
	{
		parent::__construct("The service does not have a service description for the requested API version: " . $version);
	}
}