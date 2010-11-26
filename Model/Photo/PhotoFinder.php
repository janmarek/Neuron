<?php

namespace Neuron\Model\Photo;

/**
 * PhotoFinder
 *
 * @author Jan Marek
 */
class PhotoFinder extends EntityFinder
{
	protected $alias = "p";

	public function __construct($service)
	{
		parent::__construct($service);
		$this->qb->leftJoin("p.gallery", "g");
	}

	public function whereGallery(Gallery $gallery)
	{
		$this->qb->andWhere("g.id = " . $gallery->getId());
		return $this;
	}

}