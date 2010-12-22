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

	/** @Column(type="integer") */
	private $itemOrder;

	/**
	 * @Column
	 */
	private $hash;



	public function getGallery()
	{
		return $this->gallery;
	}



	public function setGallery(Gallery $gallery)
	{
		$this->gallery = $gallery;
	}


	public function getDescription()
	{
		return $this->description;
	}



	public function setDescription($description)
	{
		$this->description = $description;
	}



	public function setHash($hash)
	{
		$this->hash = $hash;
	}



	public function getHash()
	{
		return $this->hash;
	}



	public function setItemOrder($itemOrder)
	{
		$this->itemOrder = $itemOrder;
	}



	public function getItemOrder()
	{
		return $this->itemOrder;
	}

}