<?php
namespace PublishServiceClient\Exception;

class MissingConfigurationException extends \Exception
{
	/** @var string */
	public $missingConfigurationItem;

	public function __construct($missingConfigurationItem)
	{
		$this->missingConfigurationItem = $missingConfigurationItem;
		parent::__construct("Required configuration item " . $missingConfigurationItem . " missing");
	}
}