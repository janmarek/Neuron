<?php

namespace Neuron\Tests\Model\Clip;

use Neuron\Model\Clip\Service;

/**
 * Clip service test
 */
class ServiceTest extends \Neuron\Testing\ServiceTestCase
{
	/**
	 * @var Neuron\Model\Clip\Service
	 */
	protected $object;



	protected function setUp()
	{
		$this->object = new Service($this->getEntityManager());
	}



	protected function tearDown()
	{

	}

}
