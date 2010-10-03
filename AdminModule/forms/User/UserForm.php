<?php

use Neuron\Form;

/**
 * User form
 *
 * @author Jan Marek
 */
abstract class UserForm extends BaseForm
{
	protected function addFields()
	{
		$this->addText("name");
		$this->addTextArea("text");
		$this->addText("mail");
		$this->addText("username");
		$this->addPassword("password");
	}
}