<?php

namespace Neuron\Model;

use Doctrine\ORM\Query;
use Gridito\DoctrineQueryBuilderModel;
use Nette\Paginator;

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
	 * @return array
	 */
	public function getPaginatedResult(\Nette\Paginator $paginator)
	{
		return $this->qb->getQuery()
			->setFirstResult($paginator->getOffset())
			->setMaxResults($paginator->getItemsPerPage())
			->getResult();
	}



	/**
	 * @return array
	 */
	public function getLimitedResult($limit)
	{
		return $this->qb->getQuery()->setMaxResults($limit)->getResult();
	}



	/**
	 * @return int
	 */
	public function count()
	{
		$qb = clone $this->qb;
		$alias = $qb->getRootAlias();
		return $qb->select("count($alias) fullcount")->getQuery()->getSingleResult(Query::HYDRATE_SINGLE_SCALAR);
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
	 * Get Gridito model
	 * @return Gridito\IModel
	 */
	public function getGriditoModel()
	{
		return new DoctrineQueryBuilderModel($this->qb);
	}

}