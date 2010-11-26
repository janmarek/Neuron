<?php

namespace Neuron\Model;

use Nette\Caching\Cache;
use Doctrine\ORM\Query;
use Gridito\DoctrineModel;

/**
 * Simple service
 *
 * @author Jan Marek
 *
 * @property-read Neuron\Model\EntityFinder $finder
 */
abstract class Service extends \Nette\Object
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
	public function getEntityManager()
	{
		return $this->entityManager;
	}



	/**
	 * @return string
	 */
	public function getEntityName()
	{
		return $this->entityName;
	}



	/**
	 * Find entity
	 * @param int id
	 * @return BaseEntity
	 */
	public function find($id)
	{
		return $this->entityManager->getRepository($this->entityName)->find($id);
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
	 * @return BaseEntity
	 */
	public function create($values)
	{
		$entity = $this->createBlank();
		$this->update($entity, $values);
		return $entity;
	}



	/**
	 * Update entity and flush
	 * @param BaseEntity entity
	 * @param array values
	 */
	public function update($entity, $values)
	{
		$this->setData($entity, $values);
		$this->save($entity);
		return $entity;
	}



	/**
	 * Persist entity and flush
	 * @param BaseEntity $entity
	 * @return BaseEntity
	 */
	public function save($entity)
	{
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
		return $entity;
	}



	/**
	 * Delete entity and flush
	 * @param BaseEntity entity
	 */
	public function delete($entity)
	{
		$this->entityManager->remove($entity);
		$this->entityManager->flush();
		return $entity;
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