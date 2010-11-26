<?php

namespace Neuron\Model\Page;

/**
 * Page service
 *
 * @author Jan Marek
 */
class Service extends \Neuron\Model\Service
{
	public function __construct($em)
	{
		parent::__construct($em, __NAMESPACE__ . "\Page");
	}



	public function getFinder()
	{
		return new Finder($this);
	}

}