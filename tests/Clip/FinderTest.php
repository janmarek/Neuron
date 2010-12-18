<?php

namespace Neuron\Tests\Model\Clip;

use Neuron\Model\Clip\Finder;

/**
 * Clip service test
 */
class FinderTest extends \Neuron\Testing\FinderTestCase
{
	/**
	 * @var Neuron\Model\Clip\Finder
	 */
	protected $object;



	protected function setUp()
	{
		$serviceMock = $this->getServiceMock('Neuron\Model\Clip\Service', 'Neuron\Model\Clip\Clip');
		$this->object = new Finder($serviceMock);
	}



	protected function tearDown()
	{

	}

}
