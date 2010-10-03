<?php

namespace Gridito;

use Nette\Object;

class DoctrineModel extends Object implements IModel
{

	/**
	 * @var string or null
	 */
	protected $primaryKey;
	/**
	 * @var Doctrine\ORM\QueryBuilder
	 */
	protected $query;
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;



	/**
	 * @param Doctrine\ORM\EntityManager Entity manager
	 * @param string $entity
	 */
	public function __construct(\Doctrine\ORM\EntityManager $em, $entity)
	{
		if (!is_string($entity)) {
			throw new \InvalidArgumentException("Argument must be string, " . gettype($entity) . " given.");
		} elseif (!class_exists($entity)) {
			throw new \InvalidArgumentException("Class (Entity) not found!");
		}

		$this->query = $em->createQueryBuilder()->from($entity, 'e');
		try {
			$this->primaryKey = $em->getClassMetadata($entity)->getSingleIdentifierFieldName();
		} catch (MappingException $e) {

		}
	}



	/**
	 * @return string
	 * @throws
	 */
	public function getPrimaryKey()
	{
		if ($this->primaryKey === NULL) {
			throw new \InvalidStateException("Set first primary key!");
		}
		return $this->primaryKey;
	}



	/**
	 * @param string
	 */
	public function setPrimaryKey($key)
	{
		if (!is_string($key)) {
			throw new \InvalidArgumentException("Argument must be string, " . gettype($entity) . " given.");
		}
		$this->primaryKey = $key;
	}



	/**
	 * @return Doctrine\ORM\QueryBuilder
	 */
	public function getQuery()
	{
		return $this->query;
	}



	/**
	 * @param int Offset
	 * @param int Limit
	 */
	public function setLimit($offset, $limit)
	{
		if ($limit !== NULL)
			$this->query->setMaxResults($limit);
		if ($offset !== NULL)
			$this->query->setFirstResult($offset);
	}



	/**
	 * @param string Column name
	 * @param string ASC or DESC
	 */
	public function setSorting($column, $type)
	{
		$this->query->addOrderBy('e.' . $column, $type);
	}



	/**
	 * @param mixed primary value
	 * @return (Entity)
	 */
	public function processActionParam($param)
	{
		if ($param === null) {
			return null;
		}
		try {
			return $this->query->select('e')
				->where('e.' . $this->getPrimaryKey() . ' = :primaryValue')
				->setMaxResults(1)
				->setFirstResult(0)
				->setParameter('primaryValue', $param)->getQuery()
				->getSingleResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return false;
			Debug::processException($e);
		}
	}



	/**
	 * Setup grid
	 * @param Gridito\Grid
	 */
	public function setupGrid(Grid $grid)
	{
		$grid->setPrimaryKey($this->getPrimaryKey());
	}



	/**
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->query->select('e')->getQuery()->getResult());
	}



	/**
	 * @return int
	 */
	public function count()
	{
		return $this->query->select('count(e) fullcount')->getQuery()->getSingleResult(\Doctrine\ORM\Query::HYDRATE_SINGLE_SCALAR);
	}

}