<?php

use Neuron\Form;

/**
 * Create user form
 *
 * @author Jan Marek
 */
class CreateUserForm extends UserForm
{
	protected function addFields()
	{
		$this->addHidden("id");
		parent::addFields();
	}

	protected function handler($values)
	{
		$this->getService("UserService")->create($values, $this);

		$this->presenter->flashMessage("");
		$this->presenter->redirect("this");
	}
}