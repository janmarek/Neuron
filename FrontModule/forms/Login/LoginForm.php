<?php

namespace Neuron\Form;

/**
 * LoginForm
 *
 * @author Jan Marek
 */
class Login extends BaseForm
{
	protected function addFields()
	{
		$this->addText("username")
			->setRequired('Vyplňte uživatelské jméno.');

		$this->addPassword('password')
			->setRequired('Vyplňte heslo.');
	}



	protected function handler($values)
	{
		$this->getUser()->login($values['username'], $values['password']);
		
		$this->presenter->flashMessage("Přihlášení bylo úspěšné.");
		$this->presenter->redirect('this');
	}

}