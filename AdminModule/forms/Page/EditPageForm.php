<?php

use Neuron\Form;

/**
 * Edit page form
 *
 * @author Jan Marek
 */
class EditPageForm extends PageForm
{
	protected function handler($values)
	{
		$this->getService("PageService")->update($values["id"], $values, $this);

		$this->presenter->flashMessage("Stránka byla úspěšně upravena.");
		$this->presenter->redirect("default");
	}
}