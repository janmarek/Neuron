<?php

namespace Neuron\Model\Clip;

/**
 * Clip finder
 *
 * @author Jan Marek
 */
class Finder extends \Neuron\Model\EntityFinder
{
	protected $alias = "c";



	public function whereInsertKey($key)
	{
		$this->qb->andWhere("c.insertKey = :key")->setParameter("key", $key);
		return $this;
	}

}