<?php

namespace Neuron\Model\Photo;

/**
 * PhotoFinder
 *
 * @author Jan Marek
 */
class PhotoFinder extends \Neuron\Model\EntityFinder
{
	protected $alias = "p";



	public function __construct($service)
	{
		parent::__construct($service);
		$this->qb->join("p.gallery", "g");
	}



	public function whereGallery(Gallery $gallery)
	{
		$this->qb->andWhere("g.id = " . $gallery->getId());
		return $this;
	}


	public function orderByOrder()
	{
		$this->qb->orderBy("p.itemOrder", "asc");
		return $this;
	}

}