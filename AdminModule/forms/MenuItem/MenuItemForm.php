<?php

namespace Neuron\Form;

/**
 * MenuItem form
 *
 * @author Jan Marek
 */
class MenuItemForm extends BaseForm
{
	protected function addFields()
	{
		$this->addText("name");
		$this->addText("url")->setEmptyValue("http://");
	}


	
	protected function getTemplateFile()
	{
		return __DIR__ . "/MenuItemForm.phtml";
	}
	
}