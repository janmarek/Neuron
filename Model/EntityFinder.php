<?php

namespace Neuron\Model;
// namespace Neuron\Bullshit;

use Doctrine\ORM\Query;

/**
 * Entity finder
 *
 * @author Jan Marek
 */
class EntityFinder extends \Nette\Object implements \Gridito\IModel
{
	/** @var Doctrine\ORM\QueryBuilder */
	protected $qb;



	public function __construct($qb)
	{
		$this->qb = $qb;
	}



	/**
	 * @return Neuron\Model\BaseEntity
	 */
	public function getOne()
	{
		return $this->qb->getQuery()->getSingleResult();
	}



	/**
	 * @return array
	 */
	public function getAll()
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
	public function id($id)
	{
		$this->qb->andWhere("e.id = " . (int) $id);
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



	public function getIterator()
	{
		return new \ArrayIterator($this->getAll());
	}



	public function processActionParam($param)
	{
		$qb = clone $this->qb;
		return $qb->where($qb->getRootAlias() . ".id = " . (int) $param)->getQuery()->getSingleResult();
	}



	public function setSorting($column, $type)
	{
		$this->orderBy($column, $type);
	}



	public function setupGrid(\Gridito\Grid $grid)
	{

	}

}