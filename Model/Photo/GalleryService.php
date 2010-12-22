<?php

namespace Neuron\Model\Photo;

/**
 * Photo service
 *
 * @author Jan Marek
 */
class GalleryService extends \Neuron\Model\Service
{
	public function __construct($em)
	{
		parent::__construct($em, __NAMESPACE__ . "\Gallery");
	}

}