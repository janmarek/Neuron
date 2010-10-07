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
class Service extends \Nette\Object implements IService
{
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
		$entity = $this->entityManager
			->createQuery("select e from $this->entityName e where e.id = :id")
			->setParameter("id", $id)
			->getSingleResult($mode === self::MODE_ENTITY ? Query::HYDRATE_OBJECT : Query::HYDRATE_ARRAY);

		return $entity;
	}



	/**
	 * Create blank entity
	 */
	public function createBlank()
	{
		$class = $this->entityName;
		return new $class;
	}



	/**
	 * Create entity and flush
	 * @param array values
	 */
	public function create($values)
	{
		$entity = $this->createBlank();
		$this->update($entity, $values);
	}



	/**
	 * Update entity and flush
	 * @param BaseEntity entity
	 * @param array values
	 */
	public function update($entity, $values)
	{
		$this->setData($entity, $values);
		$this->entityManager->persist($entity);
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
			$method = "set" . ucfirst($key);
			$entity->$method($value);
		}
	}

}