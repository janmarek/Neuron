<?php

use Neuron\Form;

/**
 * Page form
 *
 * @author Jan Marek
 */
abstract class PageForm extends BaseForm
{
	protected function addFields()
	{
		$this->addText("name")
			->setRequired();
		$this->addText("url")
			->setRequired()
			->setAttribute("class", "url")
			->setAttribute("rel", $this["name"]->htmlId);
		$this->addText("description")
			->setAttribute("class", "texyla");
		$this->addTextArea("text")
			->setRequired();
		$this->addCheckbox("allowed");
	}
}