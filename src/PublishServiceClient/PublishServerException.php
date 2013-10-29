<?php
namespace PublishServiceClient;
use \Guzzle\Http\Message\Response;

class PublishServerException extends \Exception
{
	/**
	 * @param Response $response
	 */
	public function __construct(Response $response)
	{
		parent::__construct("Server error occurred. Error code: " . $response->getStatusCode(). " Response: " . $response->getMessage());
	}
}