<?php

namespace Neuron\Form;

/**
 * Dictionary item form
 *
 * @author Jan Marek
 */
class DictionaryItemForm extends EntityForm
{
	protected function addFields()
	{
		$this->addText("name")->setRequired();
	}
	
}