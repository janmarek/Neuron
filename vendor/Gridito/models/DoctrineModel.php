<?php

namespace Gridito;

use Doctrine\ORM\EntityManager, Doctrine\ORM\Query;
use ArrayIterator;

/**
 * Doctrine model
 *
 * @author Jan Marek, Martin Sadový
 * @license MIT
 */
class DoctrineModel extends AbstractModel
{
	// <editor-fold defaultstate="collapsed" desc="variables">

	private $em;

	private $qb;

	private $entity;

	// </editor-fold>


	/**
	 * Construct
	 * @param Doctrine\ORM\EntityManager em
	 * @param string entity class name
	 */
	public function __construct(EntityManager $em, $entity)
	{
		$this->em = $em;
		$this->entity = $entity;
		$this->qb = $em->createQueryBuilder()->from($entity, "e");
		$this->setPrimaryKey($em->getClassMetadata($entity)->getSingleIdentifierFieldName());
	}


	/**
	 * Get query builder
	 * @return Doctrine\ORM\QueryBuilder
	 */
	public function getQueryBuilder()
	{
		return $this->qb;
	}



	public function processActionParam($param)
	{
		return $this->em->find($this->entity, $param);
	}



	public function count()
	{
		return $this->qb->select('count(e) fullcount')->getQuery()->getSingleResult(Query::HYDRATE_SINGLE_SCALAR);
	}



	public function getIterator()
	{
		$this->qb->setMaxResults($this->getLimit());
		$this->qb->setFirstResult($this->getOffset());

		list($sortColumn, $sortType) = $this->getSorting();
		if ($sortColumn) {
			$this->qb->orderBy($sortColumn, $sortType);
		}

		return new ArrayIterator($this->qb->select("e")->getQuery()->getResult());
	}
	
}