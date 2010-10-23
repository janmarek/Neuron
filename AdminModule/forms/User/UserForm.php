<?php

namespace Neuron\Form;

/**
 * User form
 *
 * @author Jan Marek
 */
class UserForm extends EntityForm
{
	protected function addFields()
	{
		$this->addText("name");
		$this->addText("surname");
		$this->addText("mail");
		$this->addText("username");
		$this->addText("password");
		$this->addText("phone");
	}
}