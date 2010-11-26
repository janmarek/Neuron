<?php

namespace Neuron\Model\Photo;

/**
 * Photo
 *
 * @author Jan Marek
 *
 * @Entity
 * @Table(name = "photo")
 */
class Photo extends \Neuron\Model\BaseEntity
{
	/**
	 * @Column(nullable = true)
	 */
	private $description;

	/**
	 * @ManyToOne(targetEntity = "Neuron\Model\Photo\Gallery", inversedBy = "photos")
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



	public function setGallery(Gallery $gallery)
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