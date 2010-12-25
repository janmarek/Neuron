<?php

namespace Neuron\Form;

/**
 * Gallery form
 *
 * @author Jan Marek
 */
class GalleryForm extends EntityForm
{
	protected function addFields()
	{
		$this->addText("name");
	}
}