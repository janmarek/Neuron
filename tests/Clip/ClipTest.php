<?php

namespace Neuron\Tests\Model\Clip;

use Neuron\Model\Clip\Clip;

/**
 * Clip entity test
 */
class ClipTest extends \Neuron\Testing\TestCase
{
	/**
	 * @var Neuron\Model\Clip\Clip
	 */
	protected $object;



	protected function setUp()
	{
		$this->object = new Clip;
	}



	protected function tearDown()
	{

	}


	public function testValidate()
	{
		$errorList = $this->getService("Validator")->validate($this->object);
		$this->assertGreaterThan(0, count($errorList));
	}

}
