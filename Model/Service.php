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
	 * @return string
	 */
	protected function getEntityName()
	{
		return $this->entityName;
	}


	
	protected function createQueryBuilder()
	{
		return $this->entityManager->getRepository($this->getEntityName())->createQueryBuilder("e");
	}



	/**
	 * Find all entities
	 * @return Neuron\Model\EntityFinder
	 */
	public function getFinder()
	{
		return new EntityFinder($this->createQueryBuilder());
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


	public function save($entity)
	{
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
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