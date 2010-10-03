<?php

use Neuron\Form;

/**
 * Edit user form
 *
 * @author Jan Marek
 */
class EditUserForm extends UserForm
{
	protected function handler($values)
	{
		$this->getService("UserService")->update($values["id"], $values, $this);

		$this->presenter->flashMessage("Uživatel byl úspěšně upraven.");
		$this->presenter->redirect("this");
	}
}