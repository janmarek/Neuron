<?php

namespace Neuron\Model\Tag;

/**
 * Tag finder
 *
 * @author Jan Marek
 */
class Finder extends \Neuron\Model\EntityFinder
{
	protected $alias = "t";



	public function whereUsed()
	{
		$this->qb->andWhere("t.itemCount > 0");
		return $this;
	}



	public function orderByName()
	{
		$this->qb->orderBy('t.name', 'asc');
		return $this;
	}

}