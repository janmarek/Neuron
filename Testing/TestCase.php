<?php

namespace Neuron\Testing;

use Nette\Environment;

/**
 * Test case
 *
 * @author Jan Marek
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
	public function getContext()
	{
		return Environment::getContext();
	}



	public function getService($name)
	{
		return $this->getContext()->getService($name);
	}

}