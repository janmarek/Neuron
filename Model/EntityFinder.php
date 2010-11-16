<?php

namespace Neuron\Model;

use Doctrine\ORM\Query;
use Gridito\DoctrineQueryBuilderModel;

/**
 * Entity finder
 *
 * @author Jan Marek
 */
class EntityFinder extends \Nette\Object implements \Countable
{
	/** @var Doctrine\ORM\QueryBuilder */
	protected $qb;


	
	// TODO $qb should be constructed here from $em
	public function __construct($qb)
	{
		$this->qb = $qb;
	}



	/**
	 * @return Neuron\Model\BaseEntity
	 */
	public function getSingleResult()
	{
		return $this->qb->getQuery()->getSingleResult();
	}



	/**
	 * @return array
	 */
	public function getResult()
	{
		return $this->qb->getQuery()->getResult();
	}



	/**
	 * @return int
	 */
	public function count()
	{
		$qb = clone $this->qb;
		return $qb->select('count(e) fullcount')->getQuery()->getSingleResult(Query::HYDRATE_SINGLE_SCALAR);
	}



	/**
	 * @param int id
	 * @return EntityFinder
	 */
	public function whereId($id)
	{
		$alias = $this->qb->getRootAlias();
		$this->qb->andWhere("$alias.id = :id");
		$this->qb->setParameter("id", $id);
		return $this;
	}



	/**
	 * @param string field
	 * @param string type
	 * @return EntityFinder
	 */
	public function orderBy($field, $type = self::ASC)
	{
		$this->qb->orderBy($field, $type);
		return $this;
	}



	/**
	 * @param int limit
	 * @return EntityFinder
	 */
	public function setLimit($limit)
	{
		$this->qb->setMaxResults($limit);
		return $this;
	}



	/**
	 * @param int offset
	 * @return EntityFinder
	 */
	public function setOffset($offset)
	{
		$this->qb->setFirstResult($offset);
		return $this;
	}



	/**
	 * Get Gridito model
	 * @return Gridito\IModel
	 */
	public function getGriditoModel()
	{
		return new DoctrineQueryBuilderModel($this->qb);
	}

}