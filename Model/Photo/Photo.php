<?php

namespace Neuron\Model;

/**
 * Photo
 *
 * @author Jan Marek
 *
 * @Entity
 * @Table(name = "photo")
 */
class Photo extends BaseEntity
{
	/**
	 * @var string
	 * @Column(nullable = true)
	 */
	private $description;

	/**
	 * @var \Neuron\Model\PhotoGallery
	 * @ManyToOne(targetEntity = "Neuron\Model\Photogallery", inversedBy = "photos")
	 */
	private $gallery;


	
	public function getFilePath()
	{
		return WWW_DIR . "/data/photos/" . $this->getGallery()->getId() . "/" . $this->getId() . ".jpg";
	}

	

	public function getGallery()
	{
		return $this->gallery;
	}



	public function setGallery(Photogallery $gallery)
	{
		$this->gallery = $gallery;
	}



	public function getImage()
	{
		return new \Neuron\Image\HtmlImage($this->getFilePath());
	}


	public function getDescription()
	{
		return $this->description;
	}



	public function setDescription($description)
	{
		$this->description = $description;
	}

}