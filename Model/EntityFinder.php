<?php

namespace Neuron\Model;
// namespace Neuron\Bullshit;

use Doctrine\ORM\Query;

/**
 * Entity finder
 *
 * @author Jan Marek
 */
class EntityFinder extends \Nette\Object implements \Countable, \IteratorAggregate, \Gridito\IModel
{
	/** @var Doctrine\ORM\QueryBuilder */
	protected $qb;

	/** @var Doctrine\ORM\EntityManager */
	private $em;

	/** @var string */
	private $entityName;



	public function __construct($qb, $em, $entityName)
	{
		$this->qb = $qb;
		$this->em = $em;
		$this->entityName = $entityName;
	}



	/**
	 * @return Neuron\Model\BaseEntity
	 */
	public function one()
	{
		return $this->qb->getQuery()->getSingleResult();
	}



	/**
	 * @return array
	 */
	public function all()
	{
		return $this->qb->getQuery()->getResult();
	}



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
		$this->qb->where("e.id = " . (int) $id);
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
	public function limit($limit)
	{
		$this->qb->setMaxResults($limit);
		return $this;
	}



	/**
	 * @param int offset
	 * @return EntityFinder
	 */
	public function offset($offset)
	{
		$this->qb->setFirstResult($offset);
		return $this;
	}



	public function __call($name, $args)
	{
		$arg = count($args) ? $args[0] : true;
		$this->qb->where("e.$name = :$name");
		$this->qb->setParameter($name, $arg);
		return $this;
	}



	public function getIterator()
	{
		return new \ArrayIterator($this->all());
	}



	public function processActionParam($param)
	{
		return $this->em->find($this->entityName, $param);
	}



	public function setLimit($limit)
	{
		$this->limit($limit);
	}

	

	public function setOffset($offset)
	{
		$this->offset($offset);
	}



	public function setSorting($column, $type)
	{
		$this->orderBy($column, $type);
	}



	public function setupGrid(\Gridito\Grid $grid)
	{

	}

}