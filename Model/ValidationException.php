<?php

namespace Model;

/**
 * Validation exception
 *
 * @author Jan Marek
 */
class ValidationException extends \Exception
{
	private $propertyPath;



	public function __construct($message, $propertyPath = null)
	{
		parent::__construct($message);
		$this->propertyPath = $propertyPath;
	}


	
	public function getPropertyPath()
	{
		return $this->propertyPath;
	}

}