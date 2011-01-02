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

	/** @Column(type="float", nullable=true) */
	private $lat;

	/** @Column(type="float", nullable=true) */
	private $lng;

	/** @OneToOne(targetEntity="Neuron\Model\Comment\CommentGroup", cascade={"all"}) */
	private $comments;



	public function __construct(array $values = array())
	{
		parent::__construct($values);
		$this->comments = new \Neuron\Model\Comment\CommentGroup;
	}



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



	public function getNextPhoto()
	{
		$photos = $this->gallery->getPhotos();
		$index = $photos->indexOf($this);
		return isset($photos[$index + 1]) ? $photos[$index + 1] : null;
	}



	public function getPrevPhoto()
	{
		$photos = $this->gallery->getPhotos();
		$index = $photos->indexOf($this);
		return $index > 0 ? $photos[$index - 1] : null;
	}



	public function getComments()
	{
		return $this->comments;
	}



	public function setLat($lat)
	{
		$this->lat = $lat ?: null;
	}



	public function getLat()
	{
		return $this->lat;
	}



	public function setLng($lng)
	{
		$this->lng = $lng ?: null;
	}



	public function getLng()
	{
		return $this->lng;
	}

}