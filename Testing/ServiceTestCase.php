<?php

namespace Neuron\Testing;

/**
 * Service test case
 *
 * @author Jan Marek
 */
abstract class ServiceTestCase extends TestCase
{
	public function getEntityManager()
	{
		return $this->getService('Doctrine\ORM\EntityManager');
	}

}