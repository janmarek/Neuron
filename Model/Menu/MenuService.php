<?php

namespace Neuron\Model;

use Doctrine\ORM\Query;
use DoctrineExtensions\NestedSet;

/**
 * Menu service
 *
 * @author Jan Marek
 */
class MenuService extends \Nette\Object
{
	/** @var \DoctrineExtensions\NestedSet\Manager */
	private $nestedSetManager;

	/** @var string */
	private $entityName = "Neuron\Model\MenuItem";

	/** @var \Doctrine\ORM\EntityManager */
	private $em;


	
	public function __construct($em)
	{
		$this->em = $em;
		$cfg = new NestedSet\Config($em, $this->entityName);
		$cfg->setRootFieldName(null);
		$this->nestedSetManager = new NestedSet\Manager($cfg);
	}



	public function fetchTree()
	{
		return $this->nestedSetManager->fetchTree();
	}



	public function fetchTreeAsArray()
	{
		return $this->nestedSetManager->fetchTreeAsArray();
	}


	public function getGriditoModel()
	{
		return new MenuGriditoModel($this);
	}



	public function count()
	{
		return $this->em->createQuery("select count(e) fullcount from $this->entityName e")->getSingleScalarResult();
	}



	/**
	 * Find entity
	 * @param int id
	 * @return BaseEntity
	 */
	public function find($id)
	{
		$entity = $this->em->getRepository($this->entityName)->find($id);
		return $this->nestedSetManager->wrapNode($entity);
	}



	public function createRootNode(array $values)
	{
		$entity = new MenuItem;
		$this->updateNode($entity, $values);
		return $this->nestedSetManager->createRoot($entity);
	}



	/**
	 * Create menu item
	 * @param array values
	 * @param MenuItem parent node
	 */
	public function createNode(array $values, MenuItem $parent)
	{
		$entity = new MenuItem;
		$this->updateNode($entity, $values);
		$this->nestedSetManager->wrapNode($parent)->addChild($entity);
	}



	/**
	 * Update entity and flush
	 * @param MenuItem entity
	 * @param array values
	 */
	public function updateNode(MenuItem $entity, array $values)
	{
		if (isset($values["url"])) {
			$entity->setUrl($values["url"]);
		}

		if (isset($values["name"])) {
			$entity->setName($values["name"]);
		}
		
		$this->em->flush();
	}



	/**
	 * Delete entity and flush
	 * @param MenuItem entity
	 */
	public function deleteNode(MenuItem $entity)
	{
		$this->nestedSetManager->wrapNode($entity)->delete();
	}

}