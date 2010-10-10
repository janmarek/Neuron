<?php

namespace Neuron\Form;

/**
 * Photogallery form
 *
 * @author Jan Marek
 */
class PhotogalleryForm extends EntityForm
{
	protected function addFields()
	{
		$this->addText("description");
	}



	protected function getTemplateFile()
	{
		return __DIR__ . "/photogalleryForm.phtml";
	}



	protected function handler($values)
	{
		parent::handler($values);
		$this->setRedirect("default", $this->getEntity()->getId());
	}

}