<?php

namespace Neuron\Model\Photo;

/**
 * Photo service
 *
 * @author Jan Marek
 */
class GalleryService extends \Neuron\Model\Service
{
	private $photoService;



	public function __construct($em)
	{
		parent::__construct($em, __NAMESPACE__ . "\Gallery");
		$this->photoService = new PhotoService($em);
	}



	public function getPhotoService()
	{
		return $this->photoService;
	}

}