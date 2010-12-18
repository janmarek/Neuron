<?php

namespace Neuron\Form;

/**
 * Clip form
 *
 * @author Jan Marek
 */
class ClipForm extends EntityForm
{
	protected function addFields()
	{
		$this->addText("insertKey");
		$this->addTextArea("text")->setAttribute("class", "texyla");
	}
}