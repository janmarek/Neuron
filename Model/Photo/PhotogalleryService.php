<?php

namespace Neuron\Model;

/**
 * Photo service
 *
 * @author Jan Marek
 */
class PhotogalleryService extends Service
{
	public function __construct($em)
	{
		parent::__construct($em, "Neuron\Model\Photogallery");
	}
	
}