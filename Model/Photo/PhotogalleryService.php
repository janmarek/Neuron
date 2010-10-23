<?php

namespace Neuron\Model;

/**
 * Photo service
 *
 * @author Jan Marek
 */
class PhotogalleryService extends Service
{
	private $photoService;



	public function __construct($em)
	{
		parent::__construct($em, "Neuron\Model\Photogallery");
		$this->photoService = new PhotoService($em);
	}



	public function getPhotoService()
	{
		return $this->photoService;
	}

}