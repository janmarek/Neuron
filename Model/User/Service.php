<?php

namespace Neuron\Model\User;

/**
 * User service
 *
 * @author Jan Marek
 */
class Service extends \Neuron\Model\Service
{
	public function __construct($em)
	{
		parent::__construct($em, __NAMESPACE__ . "\User");
	}



	public function findOneByUsername($username)
	{
		return $this->getFinder()->whereUsername($username)->getSingleResult();
	}



	public function update($id, $values, $form = null)
	{
		if (empty($values["password"])) unset($values["password"]);
		parent::update($id, $values, $form);
	}


	
	public function getFinder()
	{
		return new Finder($this);
	}
	
}