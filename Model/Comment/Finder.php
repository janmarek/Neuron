<?php

namespace Neuron\Model\Comment;

/**
 * Comment finder
 *
 * @author Jan Marek
 */
class Finder extends \Neuron\Model\EntityFinder
{
	protected $alias = "c";



	public function __construct($service)
	{
		parent::__construct($service);
		$this->qb->join('c.commentGroup', 'g');
	}



	public function whereGroup($group)
	{
		$this->qb->andWhere('g.id = :groupid')->setParameter('groupid', $group->getId());
		return $this;
	}



	public function orderByDateDesc()
	{
		$this->qb->addOrderBy('c.created', 'desc');
		return $this;
	}

}