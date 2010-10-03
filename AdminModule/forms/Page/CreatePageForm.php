<?php

use Neuron\Form;

/**
 * Create page form
 *
 * @author Jan Marek
 */
class CreatePageForm extends PageForm
{
	protected function addFields()
	{
		$this->addHidden("id");
		parent::addFields();
	}

	protected function handler($values)
	{
		$this->getService("PageService")->create($values, $this);

		$this->presenter->flashMessage("Stránka byla úspěšně přidána.");
		$this->presenter->redirect("default");
	}
}