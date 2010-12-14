<?php

namespace Neuron\Testing;

/**
 * Finder test case
 *
 * @author Jan Marek
 */
abstract class FinderTestCase extends TestCase
{
	public function getEntityManager()
	{
		return $this->getService('Doctrine\ORM\EntityManager');
	}



	public function getServiceMock($class, $entityName)
	{
		$mock = $this
			->getMockBuilder($class)
			->disableOriginalConstructor()
			->getMock();

		$mock->expects($this->any())
			->method('getEntityName')
			->will($this->returnValue($entityName));

		$mock->expects($this->any())
			->method('getEntityManager')
			->will($this->returnValue(
				$this->getEntityManager()
			));

		return $mock;
	}

}