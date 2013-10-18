<?php
use \Guzzle\Http\Message\Response;

class ClientException extends Exception
{
	/**
	 * @param Response $response
	 */
	public function __construct(Response $response)
	{
		parent::__construct("Server error occurred. Error code: " . $response->getStatusCode(). " Response: " . $response->getMessage());
	}
}