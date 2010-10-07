<?php

namespace Neuron\Form;

/**
 * EntityForm
 *
 * @author Jan Marek
 */
abstract class EntityForm extends BaseForm
{
	private $entity;

	private $entityService;


	
	public function bindEntity($entity)
	{
		$this->entity = $entity;
		
		foreach ($this->getComponents() as $name => $input) {
			$method = "get" . ucfirst($name);

			if (method_exists($entity, $method)) {
				$value = $entity->$method();

				if ($value instanceof \Neuron\Model\BaseEntity) {
					$value = $value->getId();
				}

				$input->setDefaultValue($value);
			}
		}
	}



	public function getEntity()
	{
		return $this->entity;
	}



	public function getEntityService()
	{
		return $this->entityService;
	}



	public function setEntityService($entityService)
	{
		$this->entityService = $entityService;
	}



	protected function handler($values)
	{
		$this->getEntityService()->update($this->getEntity(), $values);
	}

}