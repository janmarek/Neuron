<?php

namespace Neuron\Model;

/**
 * UserService
 *
 * @author Jan Marek
 */
class UserService extends Service
{
	public function __construct($em)
	{
		parent::__construct($em, "Model\User");
	}


	
	public function findOneByUsername($username)
	{
		$this->getEntityManager()->getRepository($this->getEntityName())->findBy(array(
			"username" => $username,
		));
	}



	public function update($id, $values, $form = null)
	{
		if (empty($values["password"])) unset($values["password"]);
		parent::update($id, $values, $form);
	}



	public function toArray($entity)
	{
		return array(
			"id" => $entity->getId(),
			"name" => $entity->getName(),
			"mail" => $entity->getMail(),
			"username" => $entity->getUsername(),
		);
	}
}