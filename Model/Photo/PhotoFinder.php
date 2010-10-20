<?php

namespace Neuron\Model;

/**
 * PhotoFinder
 *
 * @author Jan Marek
 */
class PhotoFinder extends EntityFinder
{
	public function restrictByGallery(Photogallery $gallery)
	{
		$this->qb->andWhere("g.id = " . $gallery->getId());
		return $this;
	}

}