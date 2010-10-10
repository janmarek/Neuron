<?php

namespace Neuron\Model;

/**
 * Photogallery
 *
 * @author Jan Marek
 *
 * @Entity
 * @Table(name = "photogallery")
 */
class Photogallery extends BaseEntity
{
	/**
	 * @var string
	 * @Column(nullable = true)
	 */
	private $description;

	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity = "Neuron\Model\Photo", mappedBy = "gallery")
	 */
	private $photos;


	
	public function __construct()
	{
		$this->photos = new \Doctrine\Common\Collections\ArrayCollection;
	}



	public function getDescription()
	{
		return $this->description;
	}



	public function setDescription($description)
	{
		$this->description = $description;
	}



	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */
	public function getPhotos()
	{
		return $this->photos;
	}



	public function addPhoto(Photo $photo)
	{
		$this->photos->add($photo);
		$photo->setGallery($this);
	}

}