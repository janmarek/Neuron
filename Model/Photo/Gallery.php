<?php

namespace Neuron\Model\Photo;

/**
 * Photogallery
 *
 * @author Jan Marek
 *
 * @Entity
 * @Table(name = "photogallery")
 */
class Gallery extends \Neuron\Model\BaseEntity
{
	/**
	 * @var Doctrine\Common\Collections\ArrayCollection
	 * @OneToMany(targetEntity = "Neuron\Model\Photo\Photo", mappedBy = "gallery")
	 * @OrderBy({"itemOrder" = "ASC"})
	 */
	private $photos;

	/** @Column(nullable=true) */
	private $name;



	public function __construct(array $values = array())
	{
		parent::__construct($values);
		$this->photos = new \Doctrine\Common\Collections\ArrayCollection;
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
		$this->sortPhotos();
	}



	public function removePhoto(Photo $photo)
	{
		$this->photos->removeElement($photo);
		$this->sortPhotos();
	}



	protected function sortPhotos()
	{
		foreach ($this->photos as $key => $photo) {
			$photo->setItemOrder($key + 1);
		}
	}



	public function setName($name)
	{
		$this->name = $name ?: null;
	}



	public function getName()
	{
		return $this->name;
	}

}