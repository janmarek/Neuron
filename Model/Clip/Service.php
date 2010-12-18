<?php

namespace Neuron\Model\Clip;

/**
 * Clip service
 *
 * @author Jan Marek
 */
class Service extends \Neuron\Model\Service
{
	public function __construct($em)
	{
		parent::__construct($em, __NAMESPACE__ . "\Clip");
	}



	public function getFinder()
	{
		return new Finder($this);
	}



	public function findOneByInsertKey($key)
	{
		return $this->getFinder()->whereInsertKey($key)->getSingleResult();
	}

}