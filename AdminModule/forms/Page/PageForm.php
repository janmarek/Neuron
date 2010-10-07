<?php

namespace Neuron\Form;

/**
 * Page form
 *
 * @author Jan Marek
 */
class PageForm extends EntityForm
{
	protected function addFields()
	{
		$this->addText("name")
			->setRequired();
		$this->addText("url")
			->setRequired()
			->setAttribute("class", "url")
			->setAttribute("rel", $this["name"]->htmlId);
		$this->addText("description");
		$this->addTextArea("text")
			->setAttribute("class", "texyla")
			->setRequired();
		$this->addCheckbox("allowed");
	}
	
}