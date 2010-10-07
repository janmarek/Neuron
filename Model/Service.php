<?php

namespace Neuron\Model;

use Nette\Caching\Cache;
use Doctrine\ORM\Query;
use Gridito\DoctrineModel;

/**
 * Simple service
 *
 * @author Jan Marek
 */
class Service extends \Nette\Object
{
	const MODE_ENTITY = 1;

	const MODE_ARRAY = 2;

	/** @var \Doctrine\ORM\EntityManager */
	private $entityManager;

	/** @var string */
	private $entityName;


	
	/**
	 * Construct
	 * @param \Doctrine\ORM\EntityManager entity manager
	 * @param string entity name
	 * @param \Symfony\Component\Validator\Validator validator
	 */
	public function __construct($em, $entityName)
	{
		$this->entityManager = $em;
		$this->entityName = $entityName;
	}



	/**
	 * @return \Doctrine\ORM\EntityManager
	 */
	final protected function getEntityManager()
	{
		return $this->entityManager;
	}



	/**
	 * Get gridito model
	 * @return DoctrineModel
	 */
	public function getGriditoModel()
	{
		return new DoctrineModel($this->entityManager, $this->entityName);
	}



	// <editor-fold defaultstate="collapsed" desc="crud">

	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return $this->entityName;
	}



	/**
	 * Find all entities
	 * @return array
	 */
	public function findAll()
	{
		return $this->entityManager->getRepository($this->entityName)->findAll();
	}



	/**
	 * Find entity
	 * @param int id
	 * @param int mode
	 * @return BaseEntity
	 */
	public function find($id, $mode = self::MODE_ENTITY)
	{
		$entity = $this->entityManager->find($this->entityName, $id);
		$this->entityManager->createQuery("select e from $this->entityName where e.id = :id")
			->setParameter(":id", $id)
			->getSingleResult($mode === self::MODE_ENTITY ? Query::HYDRATE_OBJECT : Query::HYDRATE_ARRAY);
	}



	/**
	 * Create entity and flush
	 * @param array values
	 */
	public function create($values)
	{
		$class = $this->entityName;
		$entity = new $class;
		$this->setData($entity, $values);
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
	}



	/**
	 * Update entity and flush
	 * @param BaseEntity entity
	 * @param array values
	 */
	public function update($entity, $values)
	{
		unset($values["id"]);

		$this->setData($entity, $values);
		$this->entityManager->flush();
	}



	/**
	 * Delete entity and flush
	 * @param BaseEntity entity
	 */
	public function delete($entity)
	{
		$this->entityManager->remove($entity);
		$this->entityManager->flush();
	}



	/**
	 * @param BaseEntity $entity
	 * @param array $values
	 */
	protected function setData($entity, $values)
	{
		foreach ($values as $key => $value) {
			$entity->$key = $value;
		}
	}

	// </editor-fold>

}