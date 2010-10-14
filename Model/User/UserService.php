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
		parent::__construct($em, "Neuron\Model\User");
	}



	public function update($id, $values, $form = null)
	{
		if (empty($values["password"])) unset($values["password"]);
		parent::update($id, $values, $form);
	}
	
}