<?php

namespace Neuron\Model\Page;

/**
 * Page service
 *
 * @author Jan Marek
 */
abstract class Service extends \Neuron\Model\Service
{
	public function __construct($em, $class)
	{
		parent::__construct($em, $class);
	}
}