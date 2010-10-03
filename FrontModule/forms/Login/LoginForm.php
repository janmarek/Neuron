<?php

namespace Neuron\Form;

/**
 * Login form
 *
 * @author Jan Marek
 */
class LoginForm extends BaseForm
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
		try {
			$this->getUser()->login($values['username'], $values['password']);

			$this->presenter->flashMessage("Přihlášení bylo úspěšné.");
			$this->presenter->redirect('this');
		} catch (\Nette\Security\AuthenticationException $e) {
			$this->addError($e->getMessage());
		}
	}

}